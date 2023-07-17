<?php

namespace App\Controller\SuperAdmin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuperAdminDashboardController extends AbstractController
{
    #[Route('/superadmin/dashboard', name: 'app_super_admin')]
    public function index(): Response
    {
        return $this->render('SuperAdminDashboard/SuperAdminDashboard.html.twig');
    }
}
