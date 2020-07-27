<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Role;
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
class ProfileController extends Controller
{
    /**
     * Display student profil form
     *
     * @return Response
     */
    public function profil()
    {
        return View::make('dashboard.students.profil', [
            'student' => Auth::user(),
            'roles' => Role::where('show_in_form', true)
                ->orderBy('order', 'desc')
                ->orderBy('name', 'asc')
                ->get()
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
            'phone' => 'required|min:8|max:20',
            'role' => function ($attribute, $value, $fail) {
                // All key should be id that can be selected in the form
                $allowedIds = Role::where('show_in_form', true)->pluck('id');
                $ids = array_keys($value);
                // Check if ids is a subset of alllowedIds
                $unexpectedIds = collect($ids)->diff($allowedIds);
                if ($unexpectedIds->count() > 0) {
                    $fail($attribute.' est invalide, veuillez réessayer.');
                }
            },
        ];

        $student = Auth::user();
        if (!$student->volunteer) {
            $rules['convention'] = 'accepted';
        }

        $this->validate(Request::instance(), $rules,
        [
            'convention.accepted' => 'Vous devez accepter l\'esprit de l\'intégration',
        ]);

        $volunteer = $student->volunteer;
        $student->volunteer = !empty(Request::get('convention'));
        $student->update(Request::only(
            'surname',
            'sex',
            'email',
            'phone'
        ));

        // Sync the pivot table by adding or removing entries
        $roleIds = [];
        if (Request::get('role') !== null) {
            $roleIds = array_keys(Request::get('role'));
        }
        $student->requestedRoles()->sync($roleIds);

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
