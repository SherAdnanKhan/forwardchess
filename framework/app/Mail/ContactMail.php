<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderCompletedListener
 * @package App\Mail
 */
class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var ContactMessage
     */
    protected $message;

    /**
     * Create a new message instance.
     *
     * OrderCompletedListener constructor.
     *
     * @param ContactMessage $message
     */
    public function __construct(ContactMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->message->email)
            ->subject('Contact message' . $this->message->subject)
            ->markdown('emails.contact')
            ->with([
                'message' => $this->message,
            ]);
    }
}
