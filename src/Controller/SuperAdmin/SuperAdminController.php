<?php

namespace App\Controller\SuperAdmin;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route("/superadmin")]
class SuperAdminController extends AbstractController
{
    // list out company 
    #[Route("/company", name: "app_sa_company_list")]
    public function companyList(CompanyRepository $companyRepository): Response
    {
        $companies = $companyRepository->findBy(["isActive" => true]);

        return $this->render(
            "/super_admin/company/index.html.twig",
            [
                "companies" => $companies
            ]
        );
    }

    // register company 
    #[Route("/company/register", name: "app_sa_company_create")]
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
            "super_admin/company/create.html.twig",
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
            "super_admin/company/update.html.twig",
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
