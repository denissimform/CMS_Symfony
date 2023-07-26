<?php

namespace App\Controller\SuperAdmin;

use App\Entity\Company;
use App\Entity\Subscription;
use App\Entity\SubscriptionDuration;
use App\Form\SubscriptionType;
use App\Repository\CompanyRepository;
use App\Repository\SubscriptionDurationRepository;
use App\Repository\SubscriptionRepository;
use App\Repository\TransactionRepository;
use App\Service\ChartService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_SUPER_ADMIN')]
#[Route("/superadmin")]
class SuperAdminController extends AbstractController
{

    public function __construct(
        private ChartService $chartService,
        private EntityManagerInterface $em
    ) {
    }

    // Dashboard - Home Page 
    #[Route("/", name: "app_sa_homepage")]
    public function homepage(): Response
    {
        $result = $this->CallProcedure('chartData', ['yearlyRevenue', 'monthlyBudget', 'topCompanies', 'companyGrowthData', 'subscriptionData', 'pieChartData'], [-1]);

        $topCompanies = $result['topCompanies'];

        // dd($result); 

        $monthBudget = $result['monthlyBudget'][0]->{'Amount'} ?? 0;

        $currentDate = date('Y-m-d');

        $currentYear = date('Y');
        $prevYear = date('Y') - 1;

        $yearBudget = 0; 
        $prevYearBudget = 0;
        $growthRate = 0;
        foreach ($result['yearlyRevenue'] as $data) {
            $amount[] = $data->{'Amount'};
            $year[] = $data->{'Year'};

            if($currentYear == $data->{'Year'}){
                $yearBudget = $data->{'Amount'};
            }

            if($prevYear == $data->{'Year'}){
                $prevYearBudget = $data->{'Amount'};
            }
        }

        if($prevYearBudget != 0){
            $growthRate = (($yearBudget - $prevYearBudget)/$prevYearBudget) * 100 ;
        }

        $yearBudgetChart = $this->chartService->createDonutChart($year, $amount);

        $companyGrowthData = $result['companyGrowthData'];
        $subscriptionData = $result['subscriptionData'];
        $pieChartData = $result['pieChartData'][0];

        $year = [];
        foreach ($companyGrowthData as $data) {
            $year[] = $data->{'Year'};
            $count[] = $data->{'Count'};
        }

        foreach ($subscriptionData as $data) {
            $plan[] = $data->{'type'};
            $sCount[] = $data->{'Count'};
        }


        $companyChart = $this->chartService->createPieChart(['isActive', 'InActive'], [$pieChartData->{'@companyActive'}, $pieChartData->{'@companyInactive'}]);

        $userChart = $this->chartService->createPieChart(['Admin', 'BDA', 'Employees'], [$pieChartData->{'@cntAdmin'}, $pieChartData->{'@cntBda'},  ($pieChartData->{'@total'} - $pieChartData->{'@cntAdmin'} - $pieChartData->{'@cntBda'})]);

        $subscriptionChart = $this->chartService->createPolarChart($plan, $sCount);
        $companyGrowthChart = $this->chartService->createLineChart($year, $count);

        return $this->render("/superadmin/index.html.twig", [
            'yearBudgetChart' => $yearBudgetChart,
            'yearBudget' => number_format($yearBudget),
            'growthRate' => number_format($growthRate, 2),
            'monthBudget' => $monthBudget,
            'date' => $currentDate,
            'topCompanies' => $topCompanies,
            'companyChart' => $companyChart,
            'userChart' => $userChart,
            'subscriptionChart' => $subscriptionChart,
            'companyGrowthChart' => $companyGrowthChart,
            "flag" => 'Super Admin'
        ]);
    }



    // Calling Procedure
    private function CallProcedure($procName, $keys = [], $parameters = [], $isExecute = false)
    {

        $pdo = $this->em->getConnection()->getNativeConnection();
        $syntax = '';
        for ($i = 0; $i < count($parameters); $i++) {
            $syntax .= (!empty($syntax) ? ',' : '') . '?';
        }

        $syntax = 'CALL ' . $procName . '(' . $syntax . ');';

        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, true);
        $stmt = $pdo->prepare($syntax, [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
        for ($i = 0; $i < count($parameters); $i++) {
            $stmt->bindValue((1 + $i), $parameters[$i]);
        }

        $exec = $stmt->execute();
        if (!$exec) {
            return $pdo->errorInfo();
        }

        if ($isExecute) {
            return $exec;
        }

        $results = [];
        do {
            try {
                $results[] = $stmt->fetchAll(\PDO::FETCH_OBJ);
            } catch (\Exception $ex) {
            }
        } while ($stmt->nextRowset());
        if (1 === count($results)) {
            $data = [];
            if (count($keys) == 1) {
                return $data[$keys[0]] = $results[0];
            }
            return $results[0];
        }
        if (count($keys) > 0) {
            $data = [];
            foreach ($keys as $index => $key) {
                $data[$key] = $results[$index];
            }
            return $data;
        }
        return $results;
    }

    // Compnay Listing
    #[Route("/company", name: "app_sa_company_list")]
    public function companyList(CompanyRepository $companyRepository): Response
    {
        $companies = $companyRepository->findBy(["isActive" => true]);

        return $this->render(
            "/superadmin/company/list.html.twig",
            [
                "companies" => $companies
            ]
        );
    }

    // Company Datatable
    #[Route('/company/datatable', name: 'app_sa_company_dt')]
    public function companyDatatable(Request $request, CompanyRepository $companyRepository): Response
    {
        $requestData = $request->query->all();
        $orderByField = $requestData['columns'][$requestData['order'][0]['column']]['data'];
        $orderDirection = $requestData['order'][0]['dir'];
        $searchBy = $requestData['search']['value'] ?? null;

        $users = $companyRepository->dynamicDataAjaxVise($requestData['length'], $requestData['start'], $orderByField, $orderDirection, $searchBy);
        $totalUsers = $companyRepository->getTotalUsersCount();

        $response = [
            "data" => $users,
            "recordsTotal" => $totalUsers,
            "recordsFiltered" => $totalUsers
        ];

        return $this->json($response, context: ['groups' => 'company:dt:read']);
    }

    // Company Delete
    #[Route('/company/delete/{id}', name: 'app_sa_company_delete')]
    public function toggleCompanyStatus(Company $company): Response
    {
        try {
            $company->setIsActive(!$company->isIsActive());
            $this->em->flush();
        } catch (Exception $err) {
            $this->addFlash("error", $err->getMessage());
        }

        $this->addFlash('success', 'Company status changed successfully!');

        return $this->redirectToRoute('app_sa_company_list');
    }

    // Single Company
    #[Route('/company/{id}', name: 'app_sa_company_single')]
    public function singleCompany(Company $company): Response
    {

        $result = $this->CallProcedure('chartData', ['userGrowthData', 'subscriptionData', 'pieChartData'], [$company->getId()]);

        // dd($result);

        $userGrowthData = $result['userGrowthData'];
        $subscriptionData = $result['subscriptionData'];
        $pieChartData = $result['pieChartData'][0];

        $year = [];
        $count = [];
        $plan = [];
        $sCount = [];

        foreach ($userGrowthData as $data) {
            $year[] = $data->{'Year'};
            $count[] = $data->{'Count'};
        }

        foreach ($subscriptionData as $data) {
            $plan[] = $data->{'type'};
            $sCount[] = $data->{'Count'};
        }

        $userChart = $this->chartService->createPieChart(['Admin', 'BDA', 'Employees'], [$pieChartData->{'@cntAdmin'}, $pieChartData->{'@cntBda'},  ($pieChartData->{'@total'} - $pieChartData->{'@cntAdmin'} - $pieChartData->{'@cntBda'})]);

        $subscriptionChart = $this->chartService->createPolarChart($plan, $count);
        $userGrowthChart = $this->chartService->createLineChart($year, $count);

        $flag = '';

        if (count($year) == 0) {
            $flag = 'No data';
        }


        return $this->render(
            "/superadmin/company/single.html.twig",
            [
                'company' => $company,
                'userChart' => $userChart,
                'subscriptionChart' => $subscriptionChart,
                'userGrowthChart' => $userGrowthChart,
                'flag' => $flag
            ]
        );
    }

    // Transaction Listing
    #[Route("/transaction", name: "app_sa_transaction_list")]
    public function transactionList(TransactionRepository $transactionRepository): Response
    {
        $transactions = $transactionRepository->findAll();

        return $this->render(
            "/superadmin/transaction/list.html.twig",
            [
                "transactions" => $transactions
            ]
        );
    }

    // Transaction Datatable
    #[Route('/transaction/datatable', name: 'app_sa_transaction_dt')]
    public function transactionDatatable(Request $request, TransactionRepository $transactionRepository): Response
    {
        $requestData = $request->query->all();
        $orderByField = $requestData['columns'][$requestData['order'][0]['column']]['data'];
        $orderDirection = $requestData['order'][0]['dir'];
        $searchBy = $requestData['search']['value'] ?? null;

        $transactions = $transactionRepository->dynamicDataAjaxVise($requestData['length'], $requestData['start'], $orderByField, $orderDirection, $searchBy);
        $totalUsers = $transactionRepository->getTotalUsersCount();

        $response = [
            "data" => $transactions,
            "recordsTotal" => $totalUsers,
            "recordsFiltered" => $totalUsers
        ];

        return $this->json($response, context: ['groups' => 'transactions:dt:read']);
    }

    // Subscription Listing
    #[Route("/subscription", name: "app_sa_subscription_list")]
    public function subscriptionList(SubscriptionDurationRepository $subscriptionDurationRepository): Response
    {
        $subscription = $subscriptionDurationRepository->findAll();

        return $this->render(
            "/superadmin/subscription/list.html.twig",
            [
                "subscription" => $subscription
            ]
        );
    }

    // Subscription Datatable
    #[Route('/subscription/datatable', name: 'app_sa_subscription_dt')]
    public function subscriptionDatatable(Request $request, SubscriptionDurationRepository $subscriptionDurationRepository): Response
    {
        $requestData = $request->query->all();
        $orderByField = $requestData['columns'][$requestData['order'][0]['column']]['data'];
        $orderDirection = $requestData['order'][0]['dir'];
        $searchBy = $requestData['search']['value'] ?? null;

        $plans = $subscriptionDurationRepository->dynamicDataAjaxVise($requestData['length'], $requestData['start'], $orderByField, $orderDirection, $searchBy);


        $totalUsers = $subscriptionDurationRepository->getTotalUsersCount();

        $response = [
            "data" => $plans,
            "recordsTotal" => $totalUsers,
            "recordsFiltered" => $totalUsers
        ];

        return $this->json($response, context: ['groups' => 'subscription:dt:read']);
    }

    // Subscrition Plan Delete
    #[Route('/subscription/delete/{id}', name: 'app_sa_subscription_delete')]
    public function toggleSubscriptionStatus(SubscriptionDuration $subscriptionDuration): Response
    {
        try {
            $subscriptionDuration->setIsActive(!$subscriptionDuration->isIsActive());
            $this->em->flush();
        } catch (Exception $err) {
            $this->addFlash("error", $err->getMessage());
        }

        $this->addFlash('success', 'Subscription Plan status changed successfully!');

        return $this->redirectToRoute('app_sa_subscription_list');
    }


    // Subscription Create
    #[Route('/subscription/create', name: 'app_subscription_create', methods: ['GET', 'POST'])]
    public function createSubscription(Request $request, EntityManagerInterface $entityManager, SubscriptionRepository $subscriptionRepository): Response
    {
        $form = $this->createForm(SubscriptionType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            if ($data['type'] == 'Other') {
                $subscription = new Subscription();
                $subscription->setType($data['customType']);
                $subscription->setCriteriaDept($data['criteria_dept']);
                $subscription->setCriteriaUser($data['criteria_user']);
                $subscription->setCriteriaStorage($data['criteria_storage']);
                $subscription->setIsActive(true);
                
                $entityManager->persist($subscription);
            } else {
                $subscription = $subscriptionRepository->findOneBy(["id"=>$data['subscription_id']]);
                $subscription->setCriteriaDept($data['criteria_dept']);
                $subscription->setCriteriaUser($data['criteria_user']);
                $subscription->setCriteriaStorage($data['criteria_storage']);
            }

            $duration = new SubscriptionDuration();
            $duration->setDuration($data['duration']);
            $duration->setPrice($data['price']);
            $duration->setIsActive(true);
            $duration->setSubscriptionId($subscription);

            $entityManager->persist($duration);
            $entityManager->flush();

            $this->addFlash('success', 'Subscription Plan created successfully!');

            return $this->redirectToRoute('app_sa_subscription_list');
        }

        return $this->render(
            'superadmin/subscription/create.html.twig',
            [
                'form' => $form,
            ],
            new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200)
        );
    }


    #[Route('/subscription/edit/{id}', name: 'app_subscription_edit')]
    public function editSubscription(Request $request, int $id, SubscriptionDurationRepository $subscriptionDurationRepository, SubscriptionRepository $subscriptionRepository, EntityManagerInterface $entityManager): Response
    {

        $plan = $subscriptionDurationRepository->find($id);

        $customArr = [];
        $customArr['type'] = $plan->getSubscriptionId()->getType();
        $customArr['duration'] = $plan->getDuration();
        $customArr['price'] = $plan->getPrice();

        $customData = json_encode($customArr);

        $form = $this->createForm(type: SubscriptionType::class, options: [
            'customData' => $customData
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if ($data['type'] == 'Other') {
                $subscription = new Subscription();
                $subscription->setType($data['customType']);
                $subscription->setCriteriaDept($data['criteria_dept']);
                $subscription->setCriteriaUser($data['criteria_user']);
                $subscription->setCriteriaStorage($data['criteria_storage']);
                $subscription->setIsActive(1);

                $entityManager->persist($subscription);
            } else {
                $subscription = $subscriptionRepository->findOneBy(["id"=>$data['subscription_id']]);
            }

            $duration = $plan;
            $duration->setDuration($data['duration']);
            $duration->setPrice($data['price']);
            $duration->setIsActive(1);
            $duration->setSubscriptionId($subscription);

            $entityManager->persist($duration);
            $entityManager->flush();

            $this->addFlash('success', 'Subscription Plan edited successfully!');

            return $this->redirectToRoute('app_sa_subscription_list');
        }

        return $this->render(
            'superadmin/subscription/create.html.twig',
            [
                'form' => $form,
            ],
            new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200)
        );
    }


    // AJAX call for SUbscription plan
    #[Route('/subscription/type-select', name: 'select_subscription_type')]
    public function getSubscriptionType(Request $request, SubscriptionRepository $subscriptionRepository): Response
    {
        $type = $request->query->get('type');

        $criteria = [];
        $criteria['Type'] = $type;
        if ($type !== "" && $type != 'Other') {
            $data = $subscriptionRepository->findBy([
                'type' => $type
            ]);
            $criteria['id'] = $data[0]->getId();
            $criteria['dept'] = $data[0]->getCriteriaDept();
            $criteria['user'] = $data[0]->getCriteriaUser();
            $criteria['storage'] = $data[0]->getCriteriaStorage();
        }
        if (empty($type)) {
            $criteria['Type'] = 'Empty';
        }

        $criteria = json_encode($criteria);

        return new Response($criteria);
    }
}
