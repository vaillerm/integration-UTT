<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use View;
use EtuUTT;
use Mail;
use Request;
use Redirect;
use Response;
use Auth;
use DB;

/**
 * Handle student management pages and administrators actions
 */
class StudentsController extends Controller
{

    /**
     * Display student list
     *
     * @param  string $filter
     * @return Response
     */
    public function list($filter = '')
    {
        $students = User::student()->orderBy('last_name', 'asc');
        switch ($filter) {
            case 'admin':
                $students = $students->where('admin', '=', User::ADMIN_FULL);
                break;
            case 'orga':
                $students = $students->where('orga', '=', true);
                break;
            case 'referral':
                $students = $students->where('referral', '=', true);
                break;
            case 'ce':
                $students = $students->where('ce', '=', true);
                break;
            case 'volunteer':
                $students = $students->where('volunteer', '=', true);
                break;
        }
        $students = $students->get();
        return View::make('dashboard.students.list', [
            'students' => $students,
            'filter' => $filter
        ]);
    }

    /**
     * Display student list by volunteer_preferences
     *
     * @param  json $filter
     * @return Response
     */
    public function listByPreferences($filter = '[]')
    {
        parse_str($filter, $filterArray);

        // Convert filter to query
        // For performance reasons we will not decode the volunteer_preferences
        // field for each user, but just search by 'like' inside
        $students = User::student()->orderBy('last_name', 'asc')->where('volunteer', true);
        foreach ($filterArray as $key => $value) {
            // Ignore if key is not in User::VOLUNTEER_PREFERENCES
            if(array_key_exists($key, User::VOLUNTEER_PREFERENCES)) {
                if ($value) {
                    $students = $students->where('volunteer_preferences', 'LIKE', '%"' . $key . '"%');
                }
                else {
                    $students = $students->where('volunteer_preferences', 'NOT LIKE', '%"' . $key . '"%');
                }
            }
            elseif ($key == '__ce') {
                if ($value) {
                    $students = $students->where('ce', '1')->where('team_accepted', '1')->whereNotNull('team_id');
                }
                else {
                    $students = $students->where(function ($query) { $query->where('ce', '0')->orWhere('team_accepted', '0')->orWhereNull('team_id'); });
                }
            }
            elseif ($key == '__mission') {
                if ($value) {
                    $students = $students->where('mission', '!=', '');
                }
                else {
                    $students = $students->where('mission', '');
                }
            }
        }
        $students = $students->get();

        // Prepare filter menu
        $filterMenu = User::VOLUNTEER_PREFERENCES;
        $filterMenu['__ce'] = [
            'title' => '*CE*',
            'description' => 'Filter les CE.',
        ];
        $filterMenu['__mission'] = [
            'title' => '*Mission*',
            'description' => 'Filter bénévoles avec une mission.',
        ];
        foreach ($filterMenu as $key => $value) {
            if (isset($filterArray[$key])) {
                if ($filterArray[$key]) {
                    $newFilter = array_merge($filterArray, [ $key => 0 ]);
                    $filterMenu[$key]['newfilter'] = http_build_query($newFilter);
                    $filterMenu[$key]['class'] = 'btn-success active';
                }
                else {
                    $newFilter = $filterArray;
                    unset($newFilter[$key]);
                    $filterMenu[$key]['newfilter'] = http_build_query($newFilter);
                    $filterMenu[$key]['class'] = 'btn-danger';
                }
            }
            else {
                $newFilter = array_merge($filterArray, [ $key => 1 ]);
                $filterMenu[$key]['newfilter'] = http_build_query($newFilter);
                $filterMenu[$key]['class'] = 'btn-default';
            }
        }

        return View::make('dashboard.students.list-preferences', [
            'filterMenu' => $filterMenu,
            'students' => $students,
            'filter' => $filterArray
        ]);
    }

    /**
     * Show the edit form for a student from admin dashboard
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function edit($id)
    {
        $student = User::where('id',$id)->firstOrFail();
        return View::make('dashboard.students.edit', [
            'student' => $student
        ]);
    }

    /**
     * Execute edit form for a student from admin dashboard
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function editSubmit($id)
    {
        $student = User::where('id', $id)->firstOrFail();
        $data = Request::only([
            'surname',
            'sex',
            'email',
            'phone',
            'branch',
            'level',
            'parent_name',
            'parent_phone',
            'birth',
            'allow_publicity',
            'referral',
            'facebook',
            'referral_max',
            'referral_text',
            'referral_validated',
            'city',
            'postal_code',
            'country',
            'medical_allergies',
            'medical_treatment',
            'medical_note',
            'admin',
            'orga',
            'secu',
            'wei_validated',
            'parent_authorization',
            'bus_id',
            'mission',
            'mission_order',
            'mission_respo',
        ]);
        $this->validate(Request::instance(), [
            'password' => 'confirmed',
            'surname' => 'max:50',
            'sex' => 'boolean',
            'email' => 'email',
            'phone' => 'min:8|max:20',
            'referral_max' => 'integer|max:100|min:1',
            'mission_order' => 'integer',
        ]);

        // Add or remove from sympa
        if (!$student->orga && $data['orga']) {
            $sent = Mail::raw('QUIET ADD integration-liste '.$student->email.' '.$student->first_name.' '.$student->last_name, function ($message) use ($student) {
                $message->from('integrat@utt.fr', 'Intégration UTT');
                $message->to('sympa@utt.fr');
            });
        } elseif ($student->orga && !$data['orga']) {
            $sent = Mail::raw('QUIET DELETE integration-liste '.$student->email, function ($message) use ($student) {
                $message->from('integrat@utt.fr', 'Intégration UTT');
                $message->to('sympa@utt.fr');
            });
        }

        // Update student informations
        $student->surname = $data['surname'];
        $student->sex = $data['sex'];
        $student->email = $data['email'];
        $student->phone = $data['phone'];
        $student->branch = $data['branch'];
        $student->city = $data['city'];
        $student->postal_code = $data['postal_code'];
        $student->country = $data['country'];
        $student->medical_allergies = $data['medical_allergies'];
        $student->medical_treatment = $data['medical_treatment'];
        $student->medical_note = $data['medical_note'];
        $student->wei_validated = !empty($data['wei_validated']);
        $student->parent_authorization = !empty($data['parent_authorization']);
        $student->bus_id = $data['bus_id'];

        if ($student->is_newcomer) {
            $student->parent_name = $data['parent_name'];
            $student->parent_phone = $data['parent_phone'];
            $student->birth = $data['birth'];
            $student->allow_publicity = !empty($data['allow_publicity']);
        }
        else {
            $student->level = $data['level'];
            $student->referral = !empty($data['referral']);
            $student->facebook = $data['facebook'];
            $student->referral_max = $data['referral_max'];
            $student->referral_text = $data['referral_text'];
            $student->referral_validated = !empty($data['referral_validated']);
            $student->admin = (!empty($data['admin']))?100:0;
            $student->orga = !empty($data['orga']);
            $student->secu = !empty($data['secu']);
            $student->mission = $data['mission'];
            $student->mission_order = $data['mission_order'];
            $student->mission_respo = !empty($data['mission_respo']);

            $volunteer_preferences = [];
            foreach (User::VOLUNTEER_PREFERENCES as $key => $value) {
                if(Request::get('volunteer_preferences')[$key] ?? '' == 'on') {
                    $volunteer_preferences[] = $key;
                }
            }
            $student->volunteer_preferences = $volunteer_preferences;

            if (!empty($data['password'])) {
                $student->setPassword($data['password']);
            }
        }

        $student->save();

        return Redirect::back()->withSuccess('Vos modifications ont été sauvegardées.');
    }



    /**
     * Search for a student on EtuUTT
     *
     * @return Response
     */
    public function add()
    {
        // Search student on EtuUTT
        $data = Request::only(['search']);
        $usersAssoc = [];
        $search = '';
        if ($data && !empty($data['search'])) {
            $search = $data['search'];
            $explode = explode(' ', $search, 5);
            $users = [];

            // Search every string as lastname and firstname
            $users = EtuUTT::call('/api/public/users', [
                'multifield' => $search
            ])['data'];

            // Remove duplicata and put them at the beginning
            $usersAssoc = [];
            foreach ($users as $key => $value) {
                // put rel as key in the _link array
                $value['links'] = [];
                foreach ($value['_links'] as $link) {
                    $value['links'][$link['rel']] = $link['uri'];
                }
                // If duplication
                if (isset($usersAssoc[$value['login']])) {
                    // Remove every values
                    unset($usersAssoc[$value['login']]);
                    // Put it at the beginning
                    $usersAssoc = array_merge([$value['login'] => $value], $usersAssoc);
                }
                // add it if student student remove it
                elseif ($value['isStudent'] == 1) {
                    $usersAssoc[$value['login']] = $value;
                }
            }
        }

        return View::make('dashboard.students.add', [
            'search' => $search,
            'students' => $usersAssoc
        ]);
    }


    /**
     * When search form from add is submited
     *
     * @return Response
     */
    public function addSubmit($login)
    {
        $user = $student = EtuUTT::importUser($login);

        if (!$user) {
            return $this->error('Une erreur s\'est produite pendant la création de l\'utilisateur.');
        }

        return redirect(route('dashboard.students.add'))->withSuccess($user->fullName().' a bien été ajouté au site.');
    }

    /**
     * When search form from add is submited
     *
     * @return Response
     */
    public function generatePassword($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $pwd = User::generatePassword();
        $user->setPassword($pwd);
        $user->save();

        // Send email
        $sent = Mail::send('emails.newpassword', ['user' => $user, 'password' => $pwd], function ($m) use ($user, $pwd) {
            $m->from('integration@utt.fr', 'Intégration UTT');
            $m->to($user->getBestEmail(), $user->fullName());
            $m->replyTo('integration@utt.fr', 'Intégration UTT');
            $m->subject('[Intégration UTT] Vos identifiants de connexion');
        });

        return redirect(route('dashboard.students.list'))->withSuccess('Le mot de passe de '. $user->fullName() . ' a été généré et lui a été envoyé');
    }
}
