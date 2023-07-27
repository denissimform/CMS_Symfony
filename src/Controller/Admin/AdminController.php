<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/admin")]
class AdminController extends AbstractController
{
    protected function hasAccess(string $role, string $redirectRoute, string $renderPage)
    {
        try {
            $this->denyAccessUnlessGranted($role);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage() . ' - First make payment to use all the features!');
            return $this->redirectToRoute($redirectRoute);
        }
        return $this->render($renderPage);
    }

    #[Route("/", name: "app_admin_homepage")]
    #[IsGranted('ROLE_ADMIN')]
    public function adminHomePage(): Response
    {
        // check if the user has access to the admin page
        return $this->hasAccess("FEATURE_ACCESS", "app_payment_homepage", "admin/homepage.html.twig");
    }
}
