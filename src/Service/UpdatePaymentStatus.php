<?php

namespace App\Service;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;

class UpdatePaymentStatus
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private EntityManagerInterface $em
    ) {
    }

    public function updateStatus(string $orderId, string $status, int $amount)
    {
        // Check is the valid order status is provided
        if (!in_array($status, Transaction::STATUS))
            throw new \InvalidArgumentException('Invalid status value provided');

        $transaction = $this->transactionRepository->findOneBy(['orderId' => $orderId]);

        if (!$transaction)
            throw new \Exception('Transaction not found');

        // Update order status and save to database
        $transaction->setStatus($status);
        $transaction->setAmount($amount);

        $this->em->persist($transaction);
        $this->em->flush();
    }
}
