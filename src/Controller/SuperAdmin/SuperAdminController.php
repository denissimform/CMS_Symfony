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
        return $this->render("/superadmin/index.html.twig");
    }
}
