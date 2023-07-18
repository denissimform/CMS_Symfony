<?php

namespace App\Command;

use App\Repository\CompanySubscriptionRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\UserRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:check-user-subscriber',
    description: 'Add a short description for your command',
)]
class CheckUserSubscriberCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private CompanySubscriptionRepository $companySubscriptionRepository,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);
            $this->companySubscriptionRepository->changeSubscriptionStatus();
            $io->success('Successfully changed subscription status.');

            return Command::SUCCESS;
        } catch (Exception $err) {
            $this->logger->info("Error " . $err->getMessage() . " at line " . $err->getLine());
            $io->error('While changing subscription status!');
            return Command::FAILURE;
        }
    }
}
