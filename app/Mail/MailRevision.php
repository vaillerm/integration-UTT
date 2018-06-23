<?php

namespace App\Mail;

use App\Models\MailHistory;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailRevision extends Mailable
{
    use Queueable, SerializesModels, InteractsWithQueue;

    public $trace;
    protected $mail_revision, $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Student $user, \App\Models\MailRevision $mail_revision)
    {
        $this->user = $user;
        $this->mail_revision = $mail_revision;
        $this->trace = new MailHistory([
            'student_id' => $this->user->id,
            'mail_revision_id' => $this->mail_revision->id,
            'mail' => $this->user->email,
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
        $this->subject($this->mail_revision->subject);
        //return $this->mail_revision->generateHtml($this->user, $this->trace->id);

        return $this->view('layouts.blank')
            ->with([
                'content' => $this->mail_revision->generateHtml($this->user, $this->trace->id),
            ]);
    }

    public function fail($exception = null)
    {
        $this->trace->state = 'ERROR';
        $this->trace->error_text = $exception;
        $this->trace->save();
    }
}
