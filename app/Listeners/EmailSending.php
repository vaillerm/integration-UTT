<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\MailHistory;

class EmailSending
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
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
        if ($event->message->getHeaders()->has('X-history-id')) {
            $mailHistory = MailHistory::findOrFail($event->message->getHeaders()->get('X-history-id')->getValue());
            $mailHistory->state = 'SENDING';
            $mailHistory->save();
        }
    }
}
