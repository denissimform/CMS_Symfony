<?php

namespace App\Controller\Admin\User;

use App\Controller\BaseController;
use Exception;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\AdminRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin")]
class UserController extends BaseController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em
    ) {
    }
    // homepage route
    #[Route("/users", name: "app_admin_users")]
    public function homepage(): Response
    {
        return $this->render('admin/user/index.html.twig');
    }

    #[Route("/approveUsers", name: "app_admin_approve_users")]
    public function ApprovedUser(): Response
    {
        return $this->render('admin/user/approveuser.html.twig');
    }

    #[Route('/create', name: 'app_admin_users_create')]
    public function createAdmin(Request $request): Response
    {
        $form = $this->createForm(AdminRegistrationType::class, options: [
            'include_created_at' => false
        ]);
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

            $this->addFlash('success', 'Registration successfull!');
            return $this->redirectToRoute('app_sa_admin_homepage');
        }

        return $this->render('admin/user/create.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200));
    }

    #[Route('/edit/{id}', name: 'app_admin_users_update')]
    public function updateAdmin(User $user, Request $request): Response
    {
        $form = $this->createForm(AdminRegistrationType::class, $user, options: [
            'include_created_at' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->em->persist($user);
                $this->em->flush();
            } catch (Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }

            $this->addFlash('success', 'User edited successfully!');
            return $this->redirectToRoute('app_sa_admin_homepage');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200));
    }

    #[Route('/approve/{id}', name: 'app_admin_approve_user')]
    public function ApproveUser(User $user,EntityManagerInterface $entityManagerInterface): Response
    {
        $user->setIsApproved($user->isIsApproved() ^ true);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_admin_approve_users');
    }

    #[Route('/delete/{id}', name: 'app_admin_users_delete')]
    public function toggleAdminStatus(User $user): Response
    {
        try {
            $user->getIsActive() ? $user->setIsActive(false) : $user->setIsActive(true);
            $this->em->flush();
        } catch (Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        $this->addFlash('success', 'User status changed successfully!');

        return $this->redirectToRoute('app_sa_admin_homepage');
    }

    #[Route('/datatable', name: 'app_admin_users_datatable')]
    public function adminDatatable(Request $request): Response
    {
        $user = $this->getUser();
        $company = $user->getCompany();
        $requestData = $request->query->all();

        $orderByField = $requestData['columns'][$requestData['order'][0]['column']]['data'];
        $orderDirection = $requestData['order'][0]['dir'];
        $searchBy = $requestData['search']['value'] ?? null;

        $users = $this->userRepository->dynamicDataAjaxVise($requestData['length'], $requestData['start'], $orderByField, $orderDirection, $searchBy, $company);
        $totalUsers = $this->userRepository->getTotalUsersCount($company);

        $response = [
            "data" => $users,
            "recordsTotal" => $totalUsers,
            "recordsFiltered" => $totalUsers
        ];

        return $this->json($response, context: ['groups' => 'user:dt:read']);
    }

    #[Route('/ApproveUserdatatable', name: 'app_admin_approve_users_datatable')]
    public function adminApproveUser(Request $request): Response
    {
        $user = $this->getUser();
        $company = $user->getCompany();
        $requestData = $request->query->all();

        $orderByField = $requestData['columns'][$requestData['order'][0]['column']]['data'];
        $orderDirection = $requestData['order'][0]['dir'];
        $searchBy = $requestData['search']['value'] ?? null;

        $users = $this->userRepository->dynamicDataApproveUser($requestData['length'], $requestData['start'], $orderByField, $orderDirection, $searchBy, $company);
        $totalUsers = $this->userRepository->getTotalUsersCount($company);
        $response = [
            "data" => $users,
            "recordsTotal" => $totalUsers,
            "recordsFiltered" => $totalUsers
        ];

        return $this->json($response);
    }

    // list out company 
    #[Route("/company", name: "app_sa_company_list")]
    public function companyList(CompanyRepository $companyRepository): Response
    {
        $companies = $companyRepository->findBy(["isActive" => true]);

        return $this->render(
            "/superadmin/company/index.html.twig",
            [
                "companies" => $companies
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
            /** @var Company $company */
            $company = $form->getData();

            $entityManager->persist($company);
            $entityManager->flush();

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

    // delete company 
    #[Route("/company/{id}/delete", name: "app_sa_company_delete")]
    public function deleteCompany(Company $company, EntityManagerInterface $entityManager): Response
    {
        try {
            $company->setIsActive(false);
            $entityManager->flush();
            $this->addFlash("success", "Company deleted successfully!");
        } catch (Exception $err) {
            $this->addFlash("error", $err->getMessage());
        }

        return $this->redirectToRoute("app_sa_company_list");
    }
}