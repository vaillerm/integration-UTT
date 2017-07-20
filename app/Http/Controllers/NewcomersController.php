<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Request;
use View;
use Validator;
use Mail;
use Auth;
use Redirect;
use Carbon\Carbon;

/**
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class NewcomersController extends Controller
{

    /**
     * Show the list of the newcomers.
     *
     * @return Response
     */
    public function list()
    {
        return View::make('dashboard.newcomers.list', [
            'newcomers' => Student::newcomer()->with(['weiPayment', 'sandwichPayment', 'guaranteePayment', 'godFather', 'team'])->get(),
            'branches' => Student::newcomer()->distinct()->select('branch')->groupBy('branch')->get(),
        ]);
    }

    /**
     * Create a newcomer.
     *
     * @return Response
     */
    public function create()
    {
        $this->validate(Request::instance(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'sex' => 'required|boolean',
            'birth' => 'date',
            'registration_email' => 'email',
            'branch' => 'required',
            'postal_code' => 'required|integer|min:0|max:99999',
            'country' => 'required',
        ]);

        $newcomer_data = Request::only([
            'first_name',
            'last_name',
            'sex',
            'birth',
            'registration_email',
            'registration_cellphone',
            'registration_phone',
            'postal_code',
            'country',
            'branch',
            'ine',
        ]);
        $newcomer_data['is_newcomer'] = true;

        $newcomer = Student::create($newcomer_data);

        if ($newcomer->save()) {
            return $this->success('L\'utilisateur a été créé !');
        }
        return $this->error('Impossible de créer l\'utilisateur !');
    }

    /**
     * Create a newcomer.
     *
     * @return Response
     */
    public function createcsv()
    {
        // Parse CSV
        $result = [];
        $temp = tmpfile();
        fwrite($temp, Request::get('csv'));
        fseek($temp, 0);
        $i = 0;
        while (($data = fgetcsv($temp, 0, ";")) !== false) {
            if ($data == false) {
                return Redirect::back()
                            ->withErrors('Erreur de lecture à la ligne '.($i+1))
                            ->withInput();
            } elseif (count($data) != 11) {
                return Redirect::back()
                            ->withErrors('La ligne '.($i+1).' comporte '.count($data).' champ au lieu de 11 séparés par des ;')
                            ->withInput();
            }
            $line = [
                'first_name' => $data[0],
                'last_name' => $data[1],
                'sex' => $data[2],
                'birth' => $data[3],
                'branch' => $data[4],
                'registration_email' => $data[5],
                'registration_cellphone' => $data[6],
                'registration_phone' => $data[7],
                'postal_code' => $data[8],
                'country' => $data[9],
                'ine' => $data[10],
            ];
            // Validate
            $validator = Validator::make($line, [
                'first_name' => 'required',
                'last_name' => 'required',
                'sex' => 'required|in:M,F,m,f',
                'birth' => 'date_format:d/m/Y|required',
                'registration_email' => 'email',
                'branch' => 'required'
            ],
            [
                'sex.in' => 'Le champ sex doit valoir seulement M ou F'
            ]);
            if ($validator->fails()) {
                dd($validator->errors(), $line);
                $errors = $validator->errors();
                $errors->add('form', 'Les erreurs ont été trouvés à la ligne '.($i+1));
                return Redirect::back()
                            ->withErrors($errors)
                            ->withInput();
            }

            // Transform fields and save it to the main array
            $line['sex'] = (strtolower($line['sex']) == 'f')?1:0;
            $line['birth'] = Carbon::createFromFormat('d/m/Y', $line['birth']);
            $result[] = $line;
            $i++;
        }
        fclose($temp);

        // Save array to db
        foreach ($result as $value) {
            $value['is_newcomer'] = true;
            if (!Student::create($value)) {
                return $this->error('Impossible de créer tous les nouveaux !');
            };
        }
        return $this->success('Les nouveaux ont été créé !');
    }

    /**
     * Display one or multiple newcomer's letter
     *
     * @param  int $id
     * @param  int $limit
     * @param  string $category
     * @return Response
     */
    public function letter($id, $limit = null, $category = null)
    {
        if ($limit === null) {
            $newcomers = [Student::newcomer()->findOrFail($id)];
        } elseif ($category != null) {
            $newcomers = Student::newcomer()->where('branch', '=', strtoupper($category))->offset($id)->limit($limit)->get();
        } else {
            $newcomers = Student::newcomer()->offset($id)->limit($limit)->get();
        }

        // Parse phone number and save it to db
        foreach ($newcomers as $newcomer) {
            if (isset($newcomer->referral->phone)) {
                if (preg_match('/^(?:0([0-9])|(?:00|\+)33[\. -]?([0-9]))[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?$/', $newcomer->referral->phone, $m)
                        && $newcomer->referral->phone != '0'.$m[1].$m[2].'.'.$m[3].'.'.$m[4].'.'.$m[5].'.'.$m[6]) {
                    $referral = $newcomer->referral;
                    $referral->phone = '0'.$m[1].$m[2].'.'.$m[3].'.'.$m[4].'.'.$m[5].'.'.$m[6];
                    $referral->save();
                }
            }
        }

        return View::make('dashboard.newcomers.letter', [ 'newcomers' => $newcomers, 'i' => $id, 'count' => Student::newcomer()->count() ]);
    }

    /**
     * Display the newcomers profil edition form
     *
     * @return Response
     */
    public function profilForm()
    {
        return View::make('newcomer.profil');
    }

    /**
     * Submit the newcomers profil form
     *
     * @return Response
     */
    public function profilFormSubmit()
    {
        $this->validate(Request::instance(), [
            'email' => 'email',
            'phone' => [
                'regex:/^(?:0([0-9])|(?:00|\+)33[\. -]?([0-9]))[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?$/',
            ],
        ],
        [
            'phone.regex' => 'Le champ téléphone doit contenir un numéro de téléphone français valide.'
        ]);

        $newcomer = Auth::user();
        $newcomer->update(Request::only([
            'email',
            'parent_name',
            'parent_phone',
            'medical_allergies',
            'medical_treatment',
            'medical_note',
        ]));

        if (preg_match('/^(?:0([0-9])|(?:00|\+)33[\. -]?([0-9]))[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?$/', Request::get('phone'), $m)) {
            $newcomer->phone = '0'.$m[1].$m[2].'.'.$m[3].'.'.$m[4].'.'.$m[5].'.'.$m[6];
        } elseif (Request::get('phone') == '') {
            $newcomer->phone = '';
        }

        $newcomer->setCheck('profil_email', !empty($newcomer->email));
        $newcomer->setCheck('profil_phone', !empty($newcomer->phone));
        $newcomer->setCheck('profil_parent_name', !empty($newcomer->parent_name));
        $newcomer->setCheck('profil_parent_phone', !empty($newcomer->parent_phone));

        $newcomer->save();

        return Redirect(route('newcomer.'.$newcomer->getNextCheck()['page']))->withSuccess('Vos modifications ont été enregistrées.');
    }

    /**
     * Display the newcomers profil edition form
     *
     * @return Response
     */
    public function referralForm($step = '')
    {
        $user = Auth::user();
        if ($user->referral == null) {
            $user->setCheck('referral', true);
            $user->save();
        }

        if ($step == 'answered') {
            $user->setCheck('referral', true);
            $user->save();
        } elseif ($step == 'cancel') {
            $user->setCheck('referral', false);
            $user->save();
        }
        return View::make('newcomer.referral', ['step' => $step]);
    }

    /**
     * Send an email to the referral with newcomer's phone and email inside of it
     *
     * @return Response
     */
    public function referralFormSubmit()
    {
        // Update newcomer's email and phone
        $this->validate(Request::instance(), [
            'email' => 'email|required',
            'phone' => 'required',
        ],
        [
            'phone.regex' => 'Le champ téléphone doit contenir un numéro de téléphone français valide.'
        ]);

        $newcomer = Auth::user();
        $newcomer->update(Request::only([
            'email'
        ]));

        if (preg_match('/^(?:0([0-9])|(?:00|\+)33[\. -]?([0-9]))[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?([0-9]{2})[\. -]?$/', Request::get('phone'), $m)) {
            $newcomer->phone = '0'.$m[1].$m[2].'.'.$m[3].'.'.$m[4].'.'.$m[5].'.'.$m[6];
        } elseif (Request::get('phone') == '') {
            $newcomer->phone = '';
        }
        $newcomer->setCheck('profil_email', !empty($newcomer->email));
        $newcomer->setCheck('profil_phone', !empty($newcomer->phone));
        $newcomer->save();

        // Checks
        if (!$newcomer->referral) {
            return Redirect::back()->withError('Vous ne pouvez pas contacter votre parrain !');
        }
        if ($newcomer->referral_emailed) {
            return Redirect::back()->withError('Un email a déjà été envoyé à ton parrain !');
        }

        // Send email
        $referral = $newcomer->referral;
        $sent = Mail::send('emails.contactReferral', ['newcomer' => $newcomer, 'referral' => $referral], function ($m) use ($referral, $newcomer) {
            $m->from('integrat@utt.fr', 'Intégration UTT');
            $m->to($referral->email);
            if ($newcomer->sex) {
                $m->subject('[parrainage] Ta fillote souhaite que tu la contacte !');
            } else {
                $m->subject('[parrainage] Ton fillot souhaite que tu le contacte !');
            }

        });


        // Note in db that referral has been mailed
        $newcomer->referral_emailed = true;
        $newcomer->save();


        return Redirect::back()->withSuccess(($referral->sex?'Ta marraine':'Ton parrain').' a bien été contacté !');
    }

    public function loginAndSendCoordonate($user_id, $hash)
    {
        $user = Student::find($user_id);
        if(!$user || ($user && $user->getHashAuthentification() != $hash))
        {
            session()->flash('error', "Impossible de vous identifier !");
            return redirect()->route('newcomer.auth.login');
        }

        Auth::login($user);
        if(!$user->referral_emailed)
        {
            $referral = $user->godFather;
            $sent = Mail::send('emails.contactReferral', ['newcomer' => $user, 'referral' => $referral], function ($m) use ($referral, $user) {
                $m->from('integrat@utt.fr', 'Intégration UTT');
                $m->to($referral->email);
                if ($user->sex) {
                    $m->subject('[parrainage] Ta fillote souhaite que tu la contacte !');
                } else {
                    $m->subject('[parrainage] Ton fillot souhaite que tu le contacte !');
                }

            });


            // Note in db that referral has been mailed
            $user->referral_emailed = true;
            $user->save();

            return Redirect::route('newcomer.home')->withSuccess(($referral->sex?'Ta marraine':'Ton parrain').' a bien été contacté !');
        }
        return Redirect::route('newcomer.home')->withSuccess('Vous êtes bien connecté !');

    }

    /**
     * Display the team
     *
     * @return Response
     */
    public function teamForm($step = '')
    {
        if ($step == 'yes') {
            Auth::user()->setCheck('team_disguise', true);
            Auth::user()->save();
        } elseif ($step == 'cancel') {
            Auth::user()->setCheck('team_disguise', false);
            Auth::user()->save();
        }
        return View::make('newcomer.team', ['step' => $step]);
    }


    /**
     * Display the letter of the newcomer
     *
     * @return Response
     */
    public function myLetter()
    {
        return View::make('dashboard.newcomers.letter', [ 'newcomers' => [Auth::user()], 'i' => 0, 'count' => 1 ]);
    }


    /**
     * Display the contact form
     *
     * @return Response
     */
    public function contact()
    {
        return View::make('newcomer.contact');
    }


    /**
     * Submit the contact form
     *
     * @return Response
     */
    public function contactSubmit()
    {
        // Update newcomer's email and phone
        $this->validate(Request::instance(), [
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $newcomer = Auth::user();
        $email = Request::get('email');
        $message = Request::get('message');

        // Send email
        $sent = Mail::send('emails.contact', ['newcomer' => $newcomer, 'email' => $email, 'text' => $message], function ($m) use ($newcomer, $email, $message) {
            $m->from('integration@utt.fr', 'Site de l\'Inté');
            $m->to('integration@utt.fr', 'Intégration UTT');
            $m->replyTo(Request::get('email'), $newcomer->first_name.' '.$newcomer->last_name);
            $m->subject('[integration.utt.fr] Message d\'un nouveau');
        });

        return Redirect(route('newcomer.home'))->withSuccess('Ton message a bien été transmis !');
    }
}
