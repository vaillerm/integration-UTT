<?php

namespace App\Http\Controllers;

use App\Models\Student;
use View;
use EtuUTT;
use Mail;
use Request;
use Redirect;
use Response;
use Auth;

/**
 * Handle student management pages and administrators actions
 */
class StudentsController extends Controller
{

    /**
     * REST API method: GET on Student model
     *
     * @return Response
     */
    public function find()
    {
        $id = Request::route('id');
        $user = Auth::guard('api')->user();

        // if no id in the route parameters and user allowed, return a list of users
        if ($id == null && $user->admin) {
            // check if there is a filter
            $filter = Request::input('filter');

            // return only newcomers
            if ($filter == "newcomers") {
                return Response::json(Student::newcomer()->get());
            }

            // return only UTT students
            if ($filter == "students") {
                return Response::json(Student::student()->get());
            }

            // return all by default
            return Response::json(Student::get());

        } else {

            // if id is "0" in the route, it becomes the authenticated user's id
            if ($id == "0") {
                $id = $user->id;
            }

            $requested_user = Student::where('id', $id)->with('team', 'godFather')->first();
            
            if ($id == $user->id || $user->admin || $user->team_id == $requested_user->team_id) {
                return Response::json($requested_user);
            } else {
                return Response::json(array(["message" => "the requested user does'nt exists."]), 404);
            }
        }

        return Response::json(array(["message" => "not allowed"]), 403);
    }

    /**
         * Display student list
         *
         * @param  string $filter
         * @return Response
         */
        public function list($filter = '')
        {
            $students = Student::student()->orderBy('last_name', 'asc');
            switch ($filter) {
                case 'admin':
                    $students = $students->where('admin', '=', Student::ADMIN_FULL);
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
         * Display student profil form
         *
         * @return Response
         */
        public function profil()
        {
            return View::make('dashboard.students.profil', [
                'student' => EtuUTT::student()
            ]);
        }

        /**
         * Get submit profil form
         *
         * @return Response
         */
        public function profilSubmit()
        {

            // Validation
            $rules = [
                'surname' => 'max:50',
                'sex' => 'required|boolean',
                'email' => 'required|email',
                'phone' => 'required|min:8|max:20'
            ];

            $student = EtuUTT::student();
            if (!$student->volunteer) {
                $rules['convention'] = 'accepted';
            }

            $this->validate(Request::instance(), $rules,
            [
                'convention.accepted' => 'Vous devez accepter l\'esprit de l\'intégration'
            ]);

            $volunteer = $student->volunteer;
            $student->volunteer = !empty(Request::get('convention'));
            $student->update(Request::only('surname', 'sex', 'email', 'phone'));
            $student->save();

            // Add or remove from sympa
            if (!$volunteer && $student->volunteer) {
                $sent = Mail::raw('QUIET ADD stupre-liste '.$student->email.' '.$student->first_name.' '.$student->last_name, function ($message) use ($student) {
                    $message->from('integrat@utt.fr', 'Intégration UTT');
                    $message->to('sympa@utt.fr');
                });
            } elseif ($volunteer && !$student->volunteer) {
                $sent = Mail::raw('QUIET DELETE stupre-liste '.$student->email, function ($message) use ($student) {
                    $message->from('integrat@utt.fr', 'Intégration UTT');
                    $message->to('sympa@utt.fr');
                });
            }

            if (!$volunteer && $student->volunteer) {
                return redirect(route('dashboard.index'))->withSuccess('Votre profil a bien été mis à jour.');
            } else {
                return redirect(route('dashboard.students.profil'))->withSuccess('Votre profil a bien été mis à jour.');
            }
        }



    /**
     * Show the edit form for a student from admin dashboard
     *
     * @param  int $id
     * @return RedirectResponse|array
     */
    public function edit($id)
    {
        $student = Student::where('student_id', $id)->firstOrFail();
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
        $student = Student::where('student_id', $id)->firstOrFail();
        $data = Request::only([
            'surname',
            'sex',
            'email',
            'phone',
            'branch',
            'level',
            'facebook',
            'city',
            'postal_code',
            'country',
            'referral',
            'referral_max',
            'referral_text',
            'referral_validated',
            'admin',
            'orga']);
        $this->validate(Request::instance(), [
            'surname' => 'max:50',
            'sex' => 'boolean',
            'email' => 'email',
            'phone' => 'min:8|max:20',
            'facebook' => 'url',
            'postal_code' => 'integer',
            'referral_max' => 'integer|max:5|min:1',
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

        // Update team informations
        $student->surname = $data['surname'];
        $student->sex = $data['sex'];
        $student->email = $data['email'];
        $student->phone = $data['phone'];
        $student->branch = $data['branch'];
        $student->level = $data['level'];
        $student->facebook = $data['facebook'];
        $student->city = $data['city'];
        $student->postal_code = $data['postal_code'];
        $student->country = $data['country'];
        $student->referral = !empty($data['referral']);
        $student->referral_max = $data['referral_max'];
        $student->referral_text = $data['referral_text'];
        $student->referral_validated = !empty($data['referral_validated']);
        $student->admin = (!empty($data['admin']))?100:0;
        $student->orga = !empty($data['orga']);
        $student->save();

        return Redirect::back()->withSuccess('Vos modifications ont été sauvegardées.');
    }
}
