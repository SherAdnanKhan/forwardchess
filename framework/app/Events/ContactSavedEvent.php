<?php

namespace App\Events;

use App\Models\ContactMessage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class ContactSavedEvent
 * @package App\Events
 */
class ContactSavedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var ContactMessage
     */
    private $contactMessage;

    /**
     * Create a new event instance.
     *
     * @param ContactMessage $contactMessage
     *
     * @return void
     */
    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    /**
     * @return ContactMessage
     */
    public function getContactMessage(): ContactMessage
    {
        return $this->contactMessage;
    }
}
