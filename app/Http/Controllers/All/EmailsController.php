<?php

namespace App\Http\Controllers\All;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\MailCron;
use App\Models\MailHistory;
use App\Models\MailTemplate;
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
    public function getUnsubscribe($mail)
    {
        $user = User::where('email', $mail)->first();
        $user->allow_publicity = false;
        $user->save();

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
