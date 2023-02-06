<?php

namespace App\Listeners;

use App\Events\ContactSavedEvent;
use App\Mail\ContactMail;
use App\Models\User\User;
use Illuminate\Support\Facades\Mail;

/**
 * Class SendContactMessage
 * @package App\Listeners
 */
class SendContactMessageListener
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
     * @param  ContactSavedEvent $event
     *
     * @return void
     */
    public function handle(ContactSavedEvent $event)
    {
        Mail::to('info@forwardchess.com')
            ->send(new ContactMail($event->getContactMessage()));
    }
}
