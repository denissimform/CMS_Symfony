<?php

namespace App\Controller\SuperAdmin;

use App\Entity\Company;
use App\Entity\CompanySubscription;
use App\Form\CompanyType;
use Doctrine\DBAL\Exception;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route("/superadmin")]
class SuperAdminController extends AbstractController
{
    // homepage route
    #[Route("/{id}", name: "app_sa_homepage")]
    public function homepage(CompanySubscription $cs, EntityManagerInterface $em): Response
    {
        $cs->setStatus(CompanySubscription::PLAN_STATUS['EXPIRED']);
        $em->flush();
        return $this->render("/superadmin/index.html.twig");
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
