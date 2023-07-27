<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin")]
class AdminController extends AbstractController
{
    #[Route("/", name: "app_admin_homepage")]
    public function adminHomePage(): Response
    {
        return $this->render("admin/homepage.html.twig");
    }
   
}
