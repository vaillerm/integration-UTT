<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Request;
use View;
use Session;
use Redirect;
use EtuUTT;

/**
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class ReferralsController extends Controller
{

    /**
     * Set student as referral and redirect to referral from
     *
     * @return Response
     */
    public function firstTime()
    {
        $student = EtuUTT::student();
        $student->referral = true;
        $student->save();

        return redirect(route('referrals.edit'));
    }

    /**
     * Show the edition form.
     *
     * @return Response
     */
    public function edit()
    {
        $student = EtuUTT::student();
        $student->referral = true;
        $student->save();

        return View::make('referrals.edit', [
            'referral' => $student
        ]);
    }

    /**
     * Handle the form submission.
     *
     * @return array
     */
    public function update(Request $request)
    {
        $referral = Student::findOrFail(Session::get('student_id'));

        if ($referral->validated == 1) {
            return $this->success('Ton profil a déjà été validé, tu ne peux plus modifier tes informations.');
        }

        if ($referral->update(Request::all())) {
            if (strlen($referral->email) < 5) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas donné ton email :/');
            }
            if (strlen($referral->phone) < 5) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas donné ton numéro de téléphone :/');
            }
            if (strlen($referral->city) < 2) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas donné ta ville d\'origine :/');
            }
            if (strlen($referral->country) < 2) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas donné ton pays d\'origine :/');
            }
            if (strlen($referral->postal_code) < 5 && $referral->postal_code !== '0') {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas donné ton code postal :/ (Pour les étudiants venant de l\'étranger, indiquez 0) ');
            } elseif (strlen($referral->referral_text) < 140) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas écris un texte assez long :/');
            } else {
                return $this->success('Ton profil a été mis à jour, merci ! :-)');
            }
        }

        return $this->error('Ton profil n\'a pas pu être mis à jour !');
    }

    /**
     * Show the validation form for referral's free text.
     *
     * @return Response
     */
    public function getValidation()
    {
        $date = new \Datetime();
        $referral = Student::where('referral', true)->where('referral_validated', 0)->where('email', '!=', '')
                            ->where('phone', '!=', '')->where('referral_text', '!=', '')
                            ->orderByRaw('RAND()')->first();
        return View::make('dashboard.referrals.validation')->with('referral', $referral);
    }

    /**
     * Handle text validation.
     *
     * @return RedirectResponse
     */
    public function postValidation()
    {
        $id = Request::input('student-id');
        $referral = Student::findOrFail($id);
        if ($referral->referral_validated) {
            return Redirect::back()->withError('Quelqu\'un a déjà validé cette personne :-(');
        }
        $referral->referral_validated = 1;
        $referral->referral_text = Request::input('referral_text');
        $referral->save();

        return Redirect::back()->withSuccess('Texte validé pour ' . $referral->first_name . ' ' . $referral->last_name . ' !');
    }

    /**
     * List all the referrals.
     *
     * @return Response
     */
    public function index()
    {
        $referrals = Student::where('referral', true)->orderBy('created_at', 'asc')->get();
        return View::make('dashboard.referrals.list', [
            'referrals' => $referrals,
        ]);
    }

    /**
     * Edit an referral from the dashboard.
     *
     * @return RedirectResponse
     */
    public function postReferrals()
    {
        $action = Request::input('action');
        if ($action == 'delete') {
            $student = Student::find(Request::input('student-id'));
            $student->referral = false;
            $student->save();

            return Redirect::back()->withSuccess('Utilisateur supprimé !');
        }
    }

    /**
     * Destroy the referral.
     *
     * @return RedirectResponse
     */
    public function destroy()
    {
        $student = EtuUTT::student();
        $student->referral = false;
        $student->save();

        return Redirect::back();
    }
}
