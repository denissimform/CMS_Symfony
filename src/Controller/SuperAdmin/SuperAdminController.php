<?php

namespace App\Controller\SuperAdmin;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route("/superadmin")]
class SuperAdminController extends AbstractController
{

    public function __construct(
        private ChartBuilderInterface $chartBuilder,
        private EntityManagerInterface $em
    ) {
    }

    // Dashboard - Home Page 
    #[Route("/", name: "app_sa_homepage")]
    public function homepage(): Response
    {
        $result = $this->CallProcedure('chartData', ['userGrowthData', 'pieChartData'], [-1]);
        // dd($result);

        $userGrowthData = $result['userGrowthData'];
        $pieChartData = $result['pieChartData'][0];

        foreach ($userGrowthData as $data) {
            $year[] = $data->{'Year'};
            $count[] = $data->{'Count'};
        }

        $companyChart = $this->createPieChart(['isActive', 'InActive'], [$pieChartData->{'@companyActive'}, $pieChartData->{'@companyInactive'}]);

        $userChart = $this->createPieChart(['Admin', 'BDA', 'Employees'], [$pieChartData->{'@cntAdmin'}, $pieChartData->{'@cntBda'},  ($pieChartData->{'@total'} - $pieChartData->{'@cntAdmin'} - $pieChartData->{'@cntBda'})]);


        $subscriptionChart = $this->createBarChart();
        $userGrowthChart = $this->createLineChart($year, $count);

        return $this->render("/superadmin/index.html.twig", [
            'companyChart' => $companyChart,
            'userChart' => $userChart,
            'subscriptionChart' => $subscriptionChart,
            'userGrowthChart' => $userGrowthChart,
            "flag" => 'Super Admin'
        ]);
    }

    // Create PIE CHart
    #[Route('/create/chart/pie', name: "app_pie_chart")]
    public function createPieChart(array $labels, array $data)
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_PIE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    'data' => $data,
                    'hoverOffset' => 4
                ],
            ],
        ]);

        return $chart;
    }

    // Create Bar CHart -- Remaining
    #[Route('/create/chart/bar', name: "app_bar_chart")]
    public function createBarChart(array $labels = [], array $data = [])
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData([
            'labels' => ['jhghyj', 'jgjhg'],
            'datasets' => [
                [
                    'label' => 'Subscription Chart',
                    'backgroundColor' => [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                    'data' => [65, 59, 80, 81, 56, 55, 40],
                    'hoverOffset' => 4,
                    'borderWidth' => 1
                ],
            ],
        ]);

        return $chart;
    }

    // Line Chart - User Growth
    #[Route('/create/chart/line', name: "app_line_chart")]
    public function createLineChart(array $labels, array $data)
    {
        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'User',
                    'data' => $data,
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.1,

                    'hoverOffset' => 4,
                    'borderWidth' => 1
                ],
            ],
        ]);

        return $chart;
    }

    // Calling Procedure
    public function CallProcedure($procName, $keys = [], $parameters = [], $isExecute = false)
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

        $result = $this->CallProcedure('chartData', ['userGrowthData', 'pieChartData'], [$company->getId()]);

        // dd($result);

        $userGrowthData = $result['userGrowthData'];
        $pieChartData = $result['pieChartData'][0];

        $year = [];
        $count = [];

        foreach ($userGrowthData as $data) {
            $year[] = $data->{'Year'};
            $count[] = $data->{'Count'};
        }

        $userChart = $this->createPieChart(['Admin', 'BDA', 'Employees'], [$pieChartData->{'@cntAdmin'}, $pieChartData->{'@cntBda'},  ($pieChartData->{'@total'} - $pieChartData->{'@cntAdmin'} - $pieChartData->{'@cntBda'})]);

        $subscriptionChart = $this->createBarChart();
        $userGrowthChart = $this->createLineChart($year, $count);

        $flag = '';

        if(count($year) == 0)
        {
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

    // register company 
    #[Route("/company/create", name: "app_sa_company_create")]
    public function registerCompany(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompanyType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $formData->setRoles(['ROLE_ADMIN']);

            try {
                $this->em->persist($formData);
                $this->em->flush();
            } catch (Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirectToRoute("app_sa_company_list");
        }

        return $this->render(
            "/superadmin/company/create.html.twig",
            [
                "form" => $form->createView()
            ],
            new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200)
        );
    }

    // update company
    #[Route("/company/{id}/edit", name: "app_sa_company_update")]
    public function updateCompany(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Company $company */
            $company = $form->getData();

            $entityManager->flush();

            return $this->redirectToRoute("app_sa_company_list");
        }

        return $this->render(
            "/superadmin/company/update.html.twig",
            [
                "form" => $form->createView()
            ],
            new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200)
        );
    }

    // #[Route('/admin/delete/{id}', name: 'app_sa_admin_delete')]
    // public function toggleAdminStatus(User $user): Response
    // {
    //     try {
    //         $company->setIsActive(false);
    //         $entityManager->flush();
    //         $this->addFlash("success", "Company deleted successfully!");
    //     } catch (Exception $err) {
    //         $this->addFlash("error", $err->getMessage());
    //     }

    //     $this->addFlash('success', 'User status changed successfully!');

    //     return $this->redirectToRoute('app_sa_admin_homepage');
    // }

    // #[Route('/admin/datatable', name: 'app_sa_admin_dt')]
    // public function adminDatatable(Request $request): Response
    // {
    //     $requestData = $request->query->all();

    //     $orderByField = $requestData['columns'][$requestData['order'][0]['column']]['data'];
    //     $orderDirection = $requestData['order'][0]['dir'];
    //     $searchBy = $requestData['search']['value'] ?? null;

    //     $users = $this->userRepository->dynamicDataAjaxVise($requestData['length'], $requestData['start'], $orderByField, $orderDirection, $searchBy);
    //     $totalUsers = $this->userRepository->getTotalUsersCount();

    //     $response = [
    //         "data" => $users,
    //         "recordsTotal" => $totalUsers,
    //         "recordsFiltered" => $totalUsers
    //     ];

    //     return $this->json($response, context: ['groups' => 'user:dt:read']);
    // }
}
