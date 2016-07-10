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
class StudentsController extends Controller {

        /**
         * Display student list
         *
         * @param  string $filter
         * @return Response
         */
        public function list($filter = '')
        {
            if (!EtuUTT::student()->isAdmin())
            {
                return $this->error('Vous n\'avez pas le droit d\'accéder à cette page.');
            }

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
            $this->validate($request, [
                'surname' => 'max:50',
                'sex' => 'required_if:volunteer,1|boolean',
                'email' => 'required_if:volunteer,1|email',
                'phone' => 'required_if:volunteer,1|min:8|max:20',
                'volunteer' => 'required|boolean',
                'convention' => 'required_if:volunteer,1|accepted'
            ]);

            $student = EtuUTT::student();
            $volunteer = $student->volunteer;

            $student->update($request->only('surname', 'sex', 'email', 'phone', 'volunteer'));
            $student->save();

            // Add or remove from sympa
            if(!$volunteer && $student->volunteer) {
                $sent = Mail::raw('QUIET ADD stupre-liste '.$student->email.' '.$student->first_name.' '.$student->last_name, function ($message) use($student) {
                    $message->from('integrat@utt.fr', 'Intégration UTT');
                    $message->to('sympa@utt.fr');
                });
            }
            elseif ($volunteer && !$student->volunteer) {
                $sent = Mail::raw('QUIET DELETE stupre-liste '.$student->email, function ($message) use($student) {
                    $message->from('integrat@utt.fr', 'Intégration UTT');
                    $message->to('sympa@utt.fr');
                });
            }

            if(!$volunteer && $student->volunteer) {
                return redirect(route('dashboard.index'))->withSuccess('Votre profil a bien été mis à jour.');
            }
            else {
                return redirect(route('dashboard.students.profil'))->withSuccess('Votre profil a bien été mis à jour.');
            }

        }
}
