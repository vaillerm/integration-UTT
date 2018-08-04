<?php

namespace App\Mail;

use App\Models\MailHistory;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DefaultMail extends Mailable
{
    use Queueable, SerializesModels, InteractsWithQueue;

    public $trace;
    protected $mail_template, $mail_cron, $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, \App\Models\MailTemplate $mail_template, \App\Models\MailCron $mail_cron)
    {
        $this->user = $user;
        $this->mail_template = $mail_template;
        $this->mail_cron = $mail_cron;
        $this->trace = new MailHistory([
            'user_id' => $this->user->id,
            'mail_template_id' => $this->mail_template->id,
            'mail_cron_id' => $this->mail_cron->id,
            'mail' => $this->user->getBestEmail(),
        ]);
        $this->trace->save();

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Add id to track the mail during events
        $this->withSwiftMessage(function ($message) {
            $message->getHeaders()
                    ->addTextHeader('X-history-id', $this->trace->id);
        });

        $this->subject($this->mail_template->subject);

        return $this->view('layouts.blank')
            ->with([
                'content' => $this->mail_template->generateHtml($this->user, $this->trace->id),
            ]);
    }

    public function fail($exception = null)
    {
        $this->trace->state = 'ERROR';
        $this->trace->error_text = $exception;
        $this->trace->save();
    }
}
