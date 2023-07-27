<?php

namespace App\Service;

use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use Psr\Log\LoggerInterface;

class UpdatePaymentStatus
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private EntityManagerInterface $em,
        private LoggerInterface $logger
    ) {
    }

    public function updateStatus(string $orderId, string $status, int $amount): bool
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

        try {
            $this->em->persist($transaction);
            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            $this->logger->error('Error updating order status: ' . $e->getMessage());
            return false;
        }
    }
}
