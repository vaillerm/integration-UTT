<?php

use \Carbon\Carbon;

/**
 * Handle registrations for the "WEI".
 *
 * Basicly, use does not create accounts ; only administrators do.
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class WEIRegistrationsController extends \BaseController {

    /**
     * List all the registrations.
     *
     * @return Response
     */
    public function index()
    {
        return View::make('dashboard.wei.index', [
            'registrations' => WEIRegistration::orderBy('complete')->get(),
            'validated'     => WEIRegistration::where('complete', true)->get()
        ]);
    }

    /**
     * Create a new subscription.
     *
     * @return RedirectResponse
     */
    public function create()
    {
        $input = Input::only(['first_name', 'last_name']);
        $registration = WEIRegistration::create($input);
        $registration->birthdate = '1997-01-01 00:00:00';
        if ($registration->save())
        {
            return Redirect::route('dashboard.wei.edit', [$registration->id]);
        }
        return $this->error('Impossible d\'ajouter l\'inscription.');
    }

    /**
     * Edit a registration.
     *
     * @param  int $id Registration id
     * @return Response
     */
    public function edit($id)
    {
        $registration = WEIRegistration::findOrFail($id);
        return View::make('dashboard.wei.edit')->with('registration', $registration);
    }

    /**
     * @todo   Refactoring :')
     * @return array|RedirectResponse
     */
    public function update($id)
    {
        $input = Input::only('is_orga', 'allergy', 'phone', 'email', 'gave_parental_authorization', 'birthdate');

        // Convert to timestamp (for Carbon usage).
        $date = new DateTime($input['birthdate']);
        $input['birthdate'] = $date->getTimestamp();

        $input['gave_parental_authorization'] = ($input['gave_parental_authorization'] === null) ? false : true;
        $input['is_orga'] = ($input['is_orga'] === null) ? false : true;


        $registration = WEIRegistration::findOrFail($id);
        $registration->update($input);

        // Payment handling.
        $mean = Input::get('mean_of_payment');
        if ($mean === 'none')
        {
            $registration->payment_id = null;
        }
        else
        {
            $payment = Payment::create([
                'mean' => $mean,
                'bank' => Input::get('payment_bank'),
                'emitter' => Input::get('payment_emitter'),
                'number' => Input::get('payment_number')
            ]);
            $registration->payment_id = $payment->id;
        }

        // Deposit handling.
        if (empty(Input::get('deposit_bank')) && empty(Input::get('deposit_emitter')) && empty(Input::get('deposit_number')))
        {
            $registration->deposit_id = null;
        }
        else
        {
            $deposit = Payment::create([
                'mean' => 'check',
                'bank' => Input::get('deposit_bank'),
                'emitter' => Input::get('deposit_emitter'),
                'number' => Input::get('deposit_number')
            ]);
            $registration->deposit_id = $deposit->id;
        }

        // Everything fullfilled?
        $start = Carbon::parse(Config::get('wei.dates.start'));
        $isUnderage = $registration->birthdate->diffInYears($start) < 18;

        if (empty($registration->phone)
         || empty($registration->email)
         || $registration->payment_id === null
         || $registration->deposit_id === null
         || ($isUnderage && $registration->gave_parental_authorization === false))
        {
            $registration->complete = false;
        }
        else
        {
            $registration->complete = true;
        }

        // Save the registration and tell the user about its status.
        if ($registration->save())
        {
            if (!$registration->complete)
            {
                return $this->success('Inscription ajoutée. Cependant, il <b>MANQUE</b> des pièces.');
            }
            return $this->success('Inscription ajoutée !');
        }
        return $this->error('Impossible de mettre à jour l\'inscription.');
    }

    /**
     * Cancel a registration for the event.
     *
     * @param  int $id Registration id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $registration = WEIRegistration::findOrFail($id);
        $registration->delete();
        return $this->success('Inscription annulée !');
    }
}
