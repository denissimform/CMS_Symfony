<?php

namespace App\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/superadmin")]
class SuperAdminController extends AbstractController
{
    // homepage route
    #[Route("/", name: "app_sa_homepage")]
    public function homepage(): Response
    {
        return $this->render('super_admin/admin/index.html.twig');
    }

    #[Route('/admin/create', name: 'app_sa_admin_create')]
    public function createAdmin(Request $request): Response
    {
        $form  = $this->createForm(AdminRegistrationType::class, options: [
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

        return $this->render('super_admin/admin/create.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200));
    }

    #[Route('/admin/edit/{id}', name: 'app_sa_admin_edit')]
    public function updateAdmin(User $user, Request $request): Response
    {
        $form  = $this->createForm(AdminRegistrationType::class, $user, options: [
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

        return $this->render('super_admin/admin/edit.html.twig', [
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? ($form->isValid() ? 200 : 422) : 200));
    }

    #[Route('/admin/delete/{id}', name: 'app_sa_admin_delete')]
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

    #[Route('/admin/datatable', name: 'app_sa_admin_dt')]
    public function adminDatatable(Request $request): Response
    {
        $requestData = $request->query->all();

        $orderByField = $requestData['columns'][$requestData['order'][0]['column']]['data'];
        $orderDirection = $requestData['order'][0]['dir'];
        $searchBy = $requestData['search']['value'] ?? null;

        $users = $this->userRepository->dynamicDataAjaxVise($requestData['length'], $requestData['start'], $orderByField, $orderDirection, $searchBy);
        $totalUsers = $this->userRepository->getTotalUsersCount();
        
        $response = [
            "data" => $users,
            "recordsTotal" => $totalUsers,
            "recordsFiltered" => $totalUsers
        ];

        return $this->json($response, context: ['groups' => 'user:dt:read']);
    }
}
