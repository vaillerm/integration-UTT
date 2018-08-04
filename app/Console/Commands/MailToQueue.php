<?php

namespace App\Console\Commands;

use App\Classes\MailHelper;
use App\Mail\DefaultMail;
use App\Jobs\mailCron;
use Illuminate\Console\Command;
use Illuminate\Queue\Queue;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\MailTemplate;

class MailToQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'integration:mails:to-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met dans la queue les emails dont la date d\'envoi programmé vient de passer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crons = \App\Models\MailCron::where('send_date', '<=', Carbon::now())
            ->where('is_sent', false)
            ->get();

        $this->line($crons->count().' MailCron ready.');

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
        $this->line('done.');
    }
}
