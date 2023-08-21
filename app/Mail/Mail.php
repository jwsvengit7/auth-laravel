<?php

namespace App\Mail;

use Illuminate\Mail\Transport\Transport;
use Swift_SmtpTransport;

class SendinBlueTransport extends Transport
{
    protected function createSmtpDriver(array $config)
    {
        return (new Swift_SmtpTransport($config['host'], $config['port']))
            ->setUsername($config['username'])
            ->setPassword($config['password'])
            ->setEncryption($config['encryption']);
    }
}
