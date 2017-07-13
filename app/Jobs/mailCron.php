<?php

namespace App\Jobs;

use App\Mail\MailRevision;
use App\Models\Email;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class mailCron implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $crons = \App\Models\MailCron::where('send_data', '<=', Carbon::now())
            ->where('is_sent', false)
            ->get();

        if($crons)
        {
            foreach ($crons as $cron)
            {
                $listes = Email::mailFromLists(explode(',', $cron->lists), $cron->mail_revision->isPublicity);
                foreach ($listes as $mail=>$val)
                {
                    $message = (new MailRevision($val['user'], $cron->mail_revision))->onQueue('low');
                    Mail::to($mail)
                        ->queue($message);
                }
                $cron->is_sent = true;
                $cron->lists_size = count($listes);
                $cron->save();
            }
        }

        $job = (new mailCron())
            ->onQueue('high')
            ->delay(Carbon::now()->addMinutes(10));
        dispatch($job);
    }
}
