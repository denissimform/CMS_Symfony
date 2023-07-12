<?php

namespace App\Controller\SuperAdmin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SuperAdminDashboardController extends AbstractDashboardController
{
    #[Route('/superadmin/dashboard', name: 'app_super_admin')]
    public function index(): Response
    {
        return $this->render('SuperAdminDashboard/SuperAdminDashboard.html.twig');
    }
}
