<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Redirect;
use Request;
use View;

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
        $student = Auth::user();
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
        $student = Auth::user();
        $student->referral = true;
        $student->save();

        return View::make('referrals.edit', [
            'referral' => $student,
        ]);
    }

    /**
     * Handle the form submission.
     *
     * @return array
     */
    public function update(Request $request)
    {
        $referral = Auth::user();

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
            if (strlen($referral->postal_code) !== 5 && $referral->postal_code != '0') {
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
     * Destroy the referral.
     *
     * @return RedirectResponse
     */
    public function destroy()
    {
        $student = Auth::user();
        $student->referral = false;
        $student->save();

        return Redirect::back();
    }
}
