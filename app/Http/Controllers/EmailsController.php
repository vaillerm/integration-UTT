<?php

namespace App\Http\Controllers;

use App\Models\Email;
use View;
use Excel;
use Request;
use Session;
use EtuUTT;
use Config;
use Blade;
use App\Jobs\SendEmail;
use App\Console\Commands\PutScheduledEmailToQueue;

class EmailsController extends Controller
{

    /**
     * @return Response
     */
    public function getIndex()
    {
        $emails = Email::all();
        return View::make('dashboard.emails.index', [ 'emails' => $emails ]);
    }

    /**
     * @return Response
     */
    public function getPreview($id)
    {
        $email = Email::findOrFail($id);

        // Send emails
        $view = '';
        if ($email->is_plaintext) {
            $view = nl2br(e($email->template));
        } else {
            $view = $email->template;
        }

        return View::make('dashboard.emails.preview', [ 'email' => $email, 'view' => $view ]);
    }
}
