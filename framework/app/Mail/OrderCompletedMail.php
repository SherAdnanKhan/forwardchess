<?php

namespace App\Mail;

use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderCompletedListener
 * @package App\Mail
 */
class OrderCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    protected $order;

    /**
     * Create a new message instance.
     *
     * OrderCompletedListener constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->order->load(['billing', 'items', 'user']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.orders.completed')->with([
            'order'        => $this->order,
            'url'          => route('site.orders.display', $this->order->refNo),
            'instructions' => route('site.download-instructions'),
        ]);
    }
}
