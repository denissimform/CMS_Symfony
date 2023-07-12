<?php

namespace App\Controller\Admin;

use App\Entity\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController{
    #[Route("/company/register", name: "app_company_register")]
    public function registerCompany(Request $request): Response{


        return $this->render('company/register.html.twig');
    }
}