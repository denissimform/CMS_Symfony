<?php

namespace App\Command;

use Exception;
use Psr\Log\LoggerInterface;
use App\Entity\CompanySubscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use App\Repository\CompanySubscriptionRepository;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:check-user-subscriber',
    description: 'Add a short description for your command',
)]
class CheckUserSubscriberCommand extends Command
{
    public function __construct(
        private CompanySubscriptionRepository $companySubscriptionRepository,
        private EntityManagerInterface $em,
        private LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $io = new SymfonyStyle($input, $output);

            $companies = $this->companySubscriptionRepository->getSubscriptionExpiredCompanies();
            
            if (!$companies) {
                $io->success('No expired subscription found to update!');
                return Command::SUCCESS;
            }
            
            /** @var CompanySubscription $company */
            foreach ($companies as $company) {
                $company->setStatus(CompanySubscription::PLAN_STATUS['EXPIRED']);
                $this->em->persist($company);
            }

            $this->em->flush();
            $io->success('Successfully changed expired subscription status!');
            return Command::SUCCESS;
        } catch (Exception $err) {
            $this->logger->info("Error " . $err->getMessage() . " at line " . $err->getLine() . ": " . $err->getFile());
            $io->error('Could not change the subscription status!');
            return Command::FAILURE;
        }
    }
}
