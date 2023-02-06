<?php

namespace App\Common\Mail;

use App\Contracts\MobileGatewayInterface;
use Illuminate\Mail\Transport\Transport;

/**
 * Class ApiEmailTransport
 * @package App\Common
 */
class ApiEmailTransport extends Transport
{
    protected $gateway;

    protected $key;

    protected $domain;

    protected $url;

    public function __construct(MobileGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function send(\Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $this->beforeSendPerformed($message);

        $fromEmail = '';
        $fromName  = '';

        foreach ($message->getFrom() as $email => $name) {
            $fromEmail = $email;
            $fromName  = $name;
            break;
        }

        $defaultSender = config('mail.from');
        $options       = [
            'fromEmail' => $fromEmail ?: $defaultSender['address'],
            'fromName'  => $fromName ?: $defaultSender['name'],
            'toEmail'   => $this->getList($message->getTo()),
            'ccEmail'   => $this->getList($message->getCc()),
            'bccEmail'  => $this->getList($message->getBcc()),
            'subject'   => $message->getSubject(),
            'body'      => $message->getBody()
        ];

        return $this->gateway->sendMail($options);
    }

    private function getList($senderList): ?array
    {
        return is_array($senderList) && count($senderList) ? array_keys($senderList) : null;
    }
}
