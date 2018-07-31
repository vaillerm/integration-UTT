<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MailHistory;

class EmailSent
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SomeEvent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $mailHistory = MailHistory::findOrFail($event->message->getHeaders()->get('X-history-id')->getValue());
        $mailHistory->state = 'SENT';
        $mailHistory->sent_at = new \Datetime;
        $mailHistory->save();
    }
}
