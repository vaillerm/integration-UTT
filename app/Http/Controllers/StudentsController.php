<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use View;
use EtuUTT;
use Mail;

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
            $students = Student::orderBy('last_name', 'asc');
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
        public function profilSubmit(Request $request)
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

            $this->validate($request, $rules,
            [
                'convention.accepted' => 'Vous devez accepter l\'esprit de l\'intégration'
            ]);

            $volunteer = $student->volunteer;
            $student->volunteer = !empty($request->get('convention'));
            $student->update($request->only('surname', 'sex', 'email', 'phone'));
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
}
