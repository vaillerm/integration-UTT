<?php

namespace App\Http\Controllers\Newcomers;

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
class ContactController extends Controller
{

    /**
     * Display the contact form
     *
     * @return Response
     */
    public function contact()
    {
        return View::make('newcomer.contact');
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
            'email' => 'required|email',
            'message' => 'required',
        ]);

        $newcomer = Auth::user();
        $email = Request::get('email');
        $message = Request::get('message');

        // Send email
        $sent = Mail::send('emails.contact', ['newcomer' => $newcomer, 'email' => $email, 'text' => $message], function ($m) use ($newcomer, $email, $message) {
            $m->from('integration@utt.fr', 'Site de l\'Inté');
            $m->to('integration@utt.fr', 'Intégration UTT');
            $m->replyTo(Request::get('email'), $newcomer->first_name.' '.$newcomer->last_name);
            $m->subject('[integration.utt.fr] Message d\'un nouveau');
        });

        return Redirect(route('newcomer.home'))->withSuccess('Ton message a bien été transmis !');
    }
}
