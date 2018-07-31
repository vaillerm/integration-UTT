<?php

namespace App\Http\Controllers\Newcomers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Faction;
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
class StepsController extends Controller
{

    /**
     * Display the newcomers profil edition form
     *
     * @return Response
     */
    public function profilForm()
    {
        return View::make('Newcomers.Steps.profil');
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
            'phone.regex' => 'Le champ téléphone doit contenir un numéro de téléphone français valide. Si tu n\'en as pas, clique sur le bouton "Nous contacter", en haut à droite, et on l\'ajoutera pour toi !'
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
        if ($user->referral_id == null) {
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
        return View::make('Newcomers.Steps.referral', ['step' => $step]);
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
        if (!$newcomer->godFather) {
            return Redirect::back()->withError('Erreur : Impossible de contacter votre parrain ! Tentez par email ou sms.');
        }
        if ($newcomer->referral_emailed) {
            return Redirect::back()->withError('Un email a déjà été envoyé à ton parrain !');
        }

        // Send email
        $referral = $newcomer->godFather;
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


        return Redirect::route('newcomer.referral')->withSuccess(($referral->sex?'Ta marraine':'Ton parrain').' a bien été contacté !');
    }

    public function loginAndSendCoordonate($user_id, $hash)
    {
        $user = User::find($user_id);
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
                $m->subject('[parrainage] Ton fillot souhaite que tu le contacte !');
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
        return View::make('Newcomers.Steps.team', [
            'step' => $step,
            'factions' => Faction::all(),
        ]);
    }

    /**
     * Display the back to school page
     *
     * @return Response
     */
    public function backToSchool($step = '')
    {
        if ($step == 'yes') {
            Auth::user()->setCheck('back_to_school', true);
            Auth::user()->save();
        } elseif ($step == 'cancel') {
            Auth::user()->setCheck('back_to_school', false);
            Auth::user()->save();
        }
        return View::make('Newcomers.Steps.backtoschool', ['step' => $step]);
    }
}
