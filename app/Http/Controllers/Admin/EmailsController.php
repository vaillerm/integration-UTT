<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\MailCron;
use App\Models\MailHistory;
use App\Models\MailRevision;
use App\Models\User;
use Carbon\Carbon;
use View;
use Excel;
use Request;
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

        return View::make('dashboard.emails.index', [
            'mail_revisions' => MailRevision::all(),
            'mail_crons'    => MailCron::all(),
        ]);
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

    public function getRevisionPreview($id, $user_id=null)
    {
        $mail = MailRevision::findOrFail($id);
        $user = User::find($user_id);

        return $mail->generateHtml($user);
    }
}
