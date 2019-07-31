<?php

namespace App\Jobs;

use App\Classes\MailHelper;
use App\Mail\DefaultMail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class generateMailBatch implements ShouldQueue
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
        $crons = \App\Models\MailCron::where('send_date', '<=', Carbon::now())
            ->where('is_sent', false)
            ->get();

        if($crons)
        {
            foreach ($crons as $cron)
            {
                $listes = MailHelper::mailFromLists(explode(',', $cron->lists), $cron->mail_template->isPublicity, $cron->mail_template, $cron->unique_send);
                foreach ($listes as $mail=>$val)
                {
                    $message = (new DefaultMail($val['user'], $cron->mail_template, $cron))->onQueue('low');
                    Mail::to($mail)
                        ->queue($message);
                }
                $cron->is_sent = true;
                $cron->lists_size = count($listes);
                $cron->save();
            }
        }
    }
}
