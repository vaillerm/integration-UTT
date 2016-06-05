<?php

/**
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class ReferralsController extends \BaseController {

    /**
     * Show the edition form.
     *
     * TODO: avoid multiple calls to Referral::find (+ filter).
     *
     * @return Response
     */
    public function edit()
    {
        // If the user is new, import some values from the API response.
        if (Referral::find(Session::get('student_id')) === null)
        {
            $json = Session::get('student_data');
            $referral = new Referral([
                'student_id'    => $json['studentId'],
                'first_name'    => $json['firstName'],
                'last_name'     => $json['lastName'],
                'surname'       => $json['surname'],
                'level'         => $json['branch'] . $json['level'],
                'free_text'     => '',
                'email'         => $json['email'],
                'max'           => 3,
                'double_degree' => null
            ]);
            $referral->save();
        }

        $referral = Referral::find(Session::get('student_id'));

        return View::make('referrals.edit', [
            'referral' => $referral
        ]);
    }

    /**
     * Handle the form submission.
     *
     * @return array
     */
    public function update()
    {
        $referral = Referral::findOrFail(Session::get('student_id'));

        if ($referral->validated == 1)
        {
            return $this->success('Ton profil a déjà été validé, tu ne peux plus modifier tes informations.');
        }

        if ($referral->update(Input::all()))
        {
            if(strlen($referral->phone) < 5) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas donné ton numéro de téléphone :/');
            }
            else if(strlen($referral->email) < 5) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas donné ton email :/');
            }
            if(strlen($referral->phone) < 5) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas donné ton numéro de téléphone :/');
            }
            if(strlen($referral->postal_code) < 5) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas donné ton code postal :/');
            }
            else if(strlen($referral->free_text) < 140) {
                return $this->warning('Ton profil a été sauvegardé, mais tu n\'as pas écris un texte assez long :/');
            }
            else {
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
        $date = new Datetime();
        $referral = Referral::where('validated', 0)->where('email', '!=', '')
                            ->where('phone', '!=', '')->where('free_text', '!=', '')
                            ->orderByRaw('RAND()')->first();
        return View::make('dashboard.validation')->with('referral', $referral);
    }

    /**
     * Handle text validation.
     *
     * @return RedirectResponse
     */
    public function postValidation()
    {
        $id = Input::get('student-id');
        $referral = Referral::findOrFail($id);
        if ($referral->validated)
        {
            return Redirect::back()->withError('Quelqu\'un a déjà validé cette personne :-(');
        }
        $referral->validated = 1;
        $referral->free_text = Input::get('free-text');
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
        $referrals = Referral::all();
        return View::make('dashboard.referrals', [
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
        $action = Input::get('action');
        if ($action == 'delete')
        {
            Referral::find(Input::get('student-id'))->delete();
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
        Referral::findOrFail(Session::get('student_id'))->delete();
        return Redirect::back();
    }
}
