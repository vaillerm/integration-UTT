<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'branches' => User::newcomer()->distinct()->select('branch')->groupBy('branch')->get(),
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
}
