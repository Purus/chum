<?php

// namespace App\Domain\User\Service;
namespace Chum;

use DI\Attribute\Inject;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ChumMailer
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail(Email $email): void
    {
        $this->mailer->send($email);
    }
}