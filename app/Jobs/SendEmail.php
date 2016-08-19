<?php

namespace App\Jobs;

use Mail;
use App\Models\Email;
use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    protected $email;
    protected $view;
    protected $dest;
    protected $destName;


    /**
     * Create a new job instance.
     *
     * @param  Email $email Email object
     * @param  string $view Generated view
     * @param  string $dest Destination email
     * @param  string $dest Destination name
     * @return void
     */
    public function __construct($email, $view, $dest, $destName)
    {
        $this->email = $email;
        $this->view = $view;
        $this->dest = $dest;
        $this->destName = $destName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        // send email
        $email = $this->email;
        $dest = $this->dest;
        $destName = $this->destName;
        $sent = Mail::send('emails.emails', ['content' => $this->view, 'subject' => $email->subject], function ($m) use ($email, $dest, $destName) {
            $m->from('integration@utt.fr', 'Intégration UTT');
            $m->to($dest, $destName);
            $m->subject($email->subject);
        });

        // On success we increment the done count
        $email->done++;
        $email->donelist .= "\n".$dest;
        $email->save();
    }
}
