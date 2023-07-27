<?php

namespace App\Services;

use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    public function __construct(private MailerInterface $mailer, private $email_id)
    {
    }

    // Send send mail
    public function sendEmail(string $toEmail, string $subject, string $htmlTemplatePath = null, array $context = []): mixed
    {
        try {
            $email = (new TemplatedEmail())
                ->from($this->email_id)
                ->to($toEmail)
                ->subject($subject)
                ->htmlTemplate($htmlTemplatePath)
                ->context($context);

            $this->mailer->send($email);

            return true;
        } catch (Exception $err) {
            throw new Exception($err->getMessage());
        }

    }
}
