<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\refreshNewcomers;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Request;
use View;
use Validator;
use Mail;
use Auth;
use Redirect;
use Carbon\Carbon;

/**
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class NewcomersController extends Controller
{

    /**
     * Show the list of the newcomers.
     *
     * @return Response
     */
    public function list()
    {
        return View::make('dashboard.newcomers.list', [
            'newcomers' => User::newcomer()->with(['weiPayment', 'sandwichPayment', 'guaranteePayment', 'godFather', 'team'])->get(),
        ]);
    }

    /**
     * Show the list of the newcomers with their progress
     *
     * @return Response
     */
    public function listProgress()
    {
        return View::make('dashboard.newcomers.list-progress', [
            'newcomers' => User::newcomer()->with(['mailHistories'])->get(),
        ]);
    }

    /**
     * Create a newcomer.
     *
     * @return Response
     */
    public function create()
    {
        $this->validate(Request::instance(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'wei_majority' => 'required|boolean',
            'registration_email' => 'email',
            'branch' => 'required',
            'postal_code' => ['required', 'regex:(0|[0-9]{5,6})'],
            'country' => 'required',
            'password' => 'required|confirmed',
        ]);

        $newcomer_data = Request::only([
            'first_name',
            'last_name',
            'wei_majority',
            'registration_email',
            'registration_phone',
            'postal_code',
            'country',
            'branch',
            'password',
        ]);
        $newcomer_data['is_newcomer'] = true;

        $newcomer = User::create($newcomer_data);
        $newcomer->setPassword($newcomer_data['password']);

        if ($newcomer->save()) {
            return $this->success('L\'utilisateur a été créé !');
        }
        return $this->error('Impossible de créer l\'utilisateur !');
    }

    /**
     * Delete infromations about user and flag to disallow sync.
     * @param  int $id
     * @return Response
     */
    public function Unsync($id)
    {
        $user = User::where('id', $id)->firstOrFail();

        if (!$user->admitted_id) {
            return $this->error('Seul les comptes nouveau ajoutés automatiquement par l\'UTT peuvent être désactivés');
        }

        // Delete everything synced except admitted_id
        $user->student_id = null;
        $user->last_name = 'Supprimé';
        $user->first_name = 'Nouveau';
        $user->postal_code = null;
        $user->country = null;
        $user->is_newcomer = false;
        $user->registration_email = null;
        $user->registration_phone = null;
        $user->branch = null;
        $user->wei_majority = null;
        $user->team_id = null;
        $user->referral_id = null;
        $user->nosync = true;
        $user->login = null;
        $user->password = null;

        $user->save();
        return $this->success('Le compte nouveau a été désactivé définitivement !');
    }

    /**
     * Create a job to sync newcomers from UTT API
     * can be launched from cli too.
     */
    public function requestSync()
    {
        $this->dispatch(new refreshNewcomers());
        return $this->success('Demande de rafraichissement demandé. Veulliez attendre quelques minutes.');
    }
}
