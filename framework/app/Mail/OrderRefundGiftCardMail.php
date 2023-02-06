<?php

namespace App\Mail;

use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderRefundGiftCardMail
 * @package App\Mail
 */
class OrderRefundGiftCardMail extends Mailable
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
     * OrderRefundGiftCardMail constructor.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->order->load(['user']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.orders.refund-gift-card')
            ->subject('Gift card refunded')
            ->with([
                'order' => $this->order,
                'url'   => route('site.home'),
            ]);
    }
}
