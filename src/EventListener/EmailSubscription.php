<?php

namespace App\EventListener;

use App\Entity\SubscriptionDuration;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: SubscriptionDuration::class)]
class EmailSubscription
{

    public function __construct(private MailerInterface $mailerInterface)
    {
    }

    public function postUpdate(SubscriptionDuration $subscription)
    {

        $email = (new Email())
            ->from('htest6236@gmail.com')
            ->to('superadmin@gmail.com')
            ->subject('Email Verification')
            ->html('Subscription plan changed.');

        $this->mailerInterface->send($email);
    }
}
