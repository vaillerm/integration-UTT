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
use DB;

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
    public function index()
    {
        $user = $user = Auth::guard('api')->user();

        if (!$user->admin && !$user->secu) {
            return Response::json(["message" => "You are not allowed."], 403);
        }

        $query = DB::table('students');

        // apply query filters
        if (Request::all()) {
            foreach (Request::all() as $key => $value) {
                $query = $query->where($key, $value);
            }
        }

        // return all by default
        return Response::json($query->get());
    }

    /**
     * Return the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::guard('api')->user();

        // if id is "0" in the route, it becomes the authenticated user's id
        if ($id == "0") {
            $id = $user->id;
        }

        $requested_user = Student::where('id', $id)->with('team', 'godFather')->first();

        // check if authorized to see this user
        if ($id == $user->id || $user->admin || $user->team_id == $requested_user->team_id) {
            return Response::json($this->removeUnauthorizedFields($requested_user));
        } else {
            return Response::json(array(["message" => "the requested user does'nt exists."]), 404);
        }
    }

    /**
     * Update the given student
     *
     * @param string $id: the id of the student to update
     * @return Response
     */
    public function update($id)
    {
        $requester = Auth::guard('api')->user();

        // check if the requester is authorized to update this User
        if (!$requester->admin && $requester->id != $id) {
            return Response::json(["message" => "You are not allowed to update this User."], 403);
        }

        $student = Student::find($id);
        if (!$student) {
            return Response::json(["message" => "Cant't find this Student."], 404);
        }

        $student->update(Request::all());

        return Response::json($student);
    }

    /**
     * Remove field that the requester can't see, depending of his roles
     *
     * @param Student $student
     * @return Object
     */
    private function removeUnauthorizedFields($student)
    {
        $user = Auth::guard('api')->user();

        unset($student->password);
        unset($student->login);
        unset($student->etuutt_access_token);
        unset($student->etuutt_refresh_token);

        // an admin can access all informations. If the user is not admin
        // remove all the fields that can only be seen by an admin
        if ($user->admin) {
            return $student;
        } else if ($user->id != $student->id) {
            unset($student->medical_allergies);
            unset($student->medical_treatment);
            unset($student->medical_note);
        }

        return $student;
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
        $student = Student::where('id',$id)->firstOrFail();
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
        $student = Student::where('id', $id)->firstOrFail();
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
            'parent_authorization'
        ]);
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
        }

        $student->save();

        return Redirect::back()->withSuccess('Vos modifications ont été sauvegardées.');
    }
}
