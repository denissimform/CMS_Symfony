<?php

namespace App\Service;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger,
        private $email_id
    ) {
    }

    // Send send mail
    public function sendEmail(string $toEmail, string $subject, string $htmlTemplatePath = null, array $context = []): bool
    {
        try {
            $email = (new TemplatedEmail())
                ->from("ManageX <$this->email_id>")
                ->to($toEmail)
                ->subject($subject)
                ->htmlTemplate($htmlTemplatePath)
                ->context($context);

            $this->mailer->send($email);

            return true;
        } catch (Exception $err) {
            $this->logger->info('Error sending mail: ' . $err->getMessage());
            return false;
        }
    }
}
