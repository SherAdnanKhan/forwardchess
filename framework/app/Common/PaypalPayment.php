<?php

namespace App\Common;

use App\Contracts\PaypalPaymentInterface;
use App\Models\Order\Order;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;

/**
 * Class PaypalPayment
 * @package App\Common
 */
class PaypalPayment implements PaypalPaymentInterface
{
    /**
     * @var Order
     */
    private $order;

    /**
     * @var Payer|null
     */
    private $payer = null;

    /**
     * @var ItemList|null
     */
    private $items = null;

    /**
     * @var Amount|null
     */
    private $amount = null;

    /**
     * @var Transaction|null
     */
    private $transaction = null;

    /**
     * @var RedirectUrls|null
     */
    private $urls = null;

    /**
     * @var Payment|null
     */
    private $payment = null;

    /**
     * @param Order $order
     *
     * @return PaypalPaymentInterface
     */
    public function setOrder(Order $order): PaypalPaymentInterface
    {
        $this->order = $order;

        $this->setPayer()
            ->setItems()
            ->setAmount()
            ->setTransaction()
            ->setUrls()
            ->setPayment();

        return $this;
    }

    /**
     * @param ApiContext $context
     *
     * @return PaypalPaymentInterface
     */
    public function createPayment(ApiContext $context): PaypalPaymentInterface
    {
        $this->payment->create($context);

        return $this;
    }

    /**
     * @param string $id
     *
     * @return PaypalPaymentInterface
     */
    public function setProfileID(string $id): PaypalPaymentInterface
    {
        $this->payment->setExperienceProfileId($id);

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRedirectUrl(): ?string
    {
        foreach ($this->payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                return $link->getHref();
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getPaymentID(): string
    {
        return $this->payment->getId();
    }

    /**
     * @param ApiContext $context
     * @param string     $id
     * @param string     $payerId
     *
     * @return Payment
     */
    public function getPaymentDetails(ApiContext $context, string $id, string $payerId): Payment
    {
        $payment = Payment::get($id, $context);

        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);

        return $payment->execute($execution, $context);
    }

    public function getOrderPaymentDetails(Payment $payment): array
    {
        $payer = $payment->getPayer()->getPayerInfo();

        $transaction      = $payment->getTransactions()[0];
        $relatedResources = $transaction->getRelatedResources()[0];

        $sale           = $relatedResources->getSale();
        $transactionFee = $sale->getTransactionFee();
        $parentPayment  = $sale->getParentPayment();

        return [
            'firstName'      => $payer->getFirstName(),
            'lastName'       => $payer->getLastName(),
            'email'          => $payer->getEmail(),
            'payerId'        => $payer->getPayerId(),
            'countryCode'    => $payer->getCountryCode(),
            'feeAmount'      => $transactionFee->getValue(),
            'feeCurrency'    => $transactionFee->getCurrency(),
            'transactionKey' => $parentPayment,
            'data'           => serialize($payment)
        ];
    }

    /**
     * @return PaypalPayment
     */
    private function setPayer(): PaypalPayment
    {
        $this->payer = new Payer();
        $this->payer->setPaymentMethod('paypal');

        return $this;
    }

    /**
     * @return PaypalPayment
     */
    private function setItems(): PaypalPayment
    {
        $this->items = new ItemList();
        foreach ($this->order->items as $item) {
            if(!$item->isBundle) {
                // adding only bundle products
                $this->items->addItem((new Item())
                    ->setName($item->name)
                    ->setCurrency('USD')
                    ->setQuantity($item->quantity)
                    ->setPrice($item->total)
                );
            }
        }

        if ($this->order->discount) {
            $this->items->addItem((new Item())
                ->setName('Discount')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice(-1 * $this->order->discount)
            );
        }

        return $this;
    }

    /**
     * @return PaypalPayment
     */
    private function setAmount(): PaypalPayment
    {
        $subTotal = ($this->order->discount)
            ? toFloatAmount($this->order->getRawOriginal('subTotal') - $this->order->getRawOriginal('discount'))
            : $this->order->subTotal;

        $details = new Details();
        $details->setSubtotal($subTotal);

        if ($this->order->taxAmount) {
            $details->setTax($this->order->taxAmount);
        }

        $this->amount = new Amount();
        $this->amount->setCurrency('USD')
            ->setTotal($this->order->total)
            ->setDetails($details);

        return $this;
    }

    /**
     * @return PaypalPayment
     */
    private function setTransaction(): PaypalPayment
    {
        $this->transaction = new Transaction();
        $this->transaction->setAmount($this->amount)
            ->setItemList($this->items)
            ->setDescription('Pay to finish your order');

        return $this;
    }

    /**
     * @return PaypalPayment
     */
    private function setUrls(): PaypalPayment
    {
        $this->urls = new RedirectUrls();
        $this->urls
            ->setReturnUrl(route('site.updateOrderPayment'))
            ->setCancelUrl(route('site.retryPayment'));

        return $this;
    }

    /**
     * @return PaypalPayment
     */
    private function setPayment(): PaypalPayment
    {
        $this->payment = new Payment();
        $this->payment->setIntent('Sale')
            ->setPayer($this->payer)
            ->setRedirectUrls($this->urls)
            ->setTransactions([$this->transaction]);

        return $this;
    }
}