<?php

namespace App\Mail;

use App\Models\Gift;
use App\Models\Order\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderGiftCardMail
 * @package App\Mail
 */
class OrderSendGiftCardMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The gift instance.
     *
     * @var Gift
     */
    protected $gift;

    /**
     * The order instance.
     *
     * @var Order
     */
    protected $order;

    /**
     * Create a new message instance.
     *
     * OrderGiftCardMail constructor.
     *
     * @param Order $order
     * @param Gift  $gift
     */
    public function __construct(Order $order, Gift $gift)
    {
        $this->order = $order;
        $this->gift  = $gift;
        $this->order->load(['user']);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.orders.send-gift-card')
            ->subject('Gift card')
            ->with([
            'order' => $this->order,
            'gift'  => $this->gift,
            'url'   => route('site.home'),
        ]);
    }
}
