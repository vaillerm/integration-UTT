<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\MailHistory;
use App\Models\MailRevision;
use App\Models\Student;
use Carbon\Carbon;
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
        return View::make('dashboard.emails.index', [
            'emails' => $emails,
            'mail_revisions' => MailRevision::all()
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
        $student = Student::find($user_id);

        return $mail->generateHtml($student);
    }

    public function getUnsubscribe($mail)
    {
        $student = Student::where('email', $mail)->first();
        $student->allow_publicity = false;
        $student->save();

        return 'Mail unsubscribed';
    }

    public function trackOpening($mail_id)
    {
        //On trace le mail
        $mail = MailHistory::find($mail_id);
        if($mail && !$mail->open_at)
        {
            $mail->open_at = Carbon::now();
            $mail->save();
        }


        $image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
        return response($image)->header('Content-Type', 'image/png');
    }
}
