<?php

namespace App\Http\Controllers\All;

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
use Authorization;
use Carbon\Carbon;

/**
 *
 * @author  Thomas Chauchefoin <thomas@chauchefoin.fr>
 * @license MIT
 */
class ContactController extends Controller
{

    /**
     * Display the contact form
     *
     * @return Response
     */
    public function contact()
    {
        return View::make('All.contact');
    }


    /**
     * Submit the contact form
     *
     * @return Response
     */
    public function contactSubmit()
    {
        // Update newcomer's email and phone
        $this->validate(Request::instance(), [
            'name' => Auth::user() ?  '' : 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $user = Auth::user();
        $name = $user ? $user->first_name.' '.$user->last_name : Request::get('name') ;
        $email = Request::get('email');
        $message = Request::get('message');
        $userStatus = $user ? (
            $user->isNewcomer() ? 'un nouveau' : 'un ancien'
            ) : 'un utilisateur non connecté';

        // Send email
        $sent = Mail::send('emails.contact', ['user' => $user, 'userStatus' => $userStatus, 'email' => $email, 'text' => $message, 'name' => $name], function ($m) use ($userStatus, $email, $message, $name) {
            $m->from('integration@utt.fr', 'Site de l\'Inté');
            $m->to('integration@utt.fr', 'Intégration UTT');
            $m->replyTo($email, $name);
            $m->subject('[integration.utt.fr] Nouveau message d\''.$userStatus);
        });

        return Redirect(route(Authorization::getHomeRoute()))->withSuccess('Ton message a bien été transmis !');
    }
}
