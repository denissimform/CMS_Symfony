<?php

namespace App\Controller\Admin\User;

use App\Controller\BaseController;
use App\Entity\User;
use App\Form\EmployeeUpdateFormType;
use App\Form\UpdatePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/admin")]
class ProfileController extends BaseController
{
    #[Route("/profile", name: "app_user_profile")]
    public function profile(): Response
    {
        return $this->render('admin/user/profile/index.html.twig',[
            'user' => $this->getUser()
        ]);
    }

    #[Route("/profile/update/{id}", name: "app_user_profile_update")]
    public function Updateprofile(Request $request, User $user, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(EmployeeUpdateFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_user_profile');
        }
        return $this->render('admin/user/profile/register.html.twig',[
            'userForm' => $form->createView()
        ]);
    }

    #[Route("/profile/updatePassword", name: "app_user_profile_update_password")]
    public function UpdatePassword(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(UpdatePasswordFormType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_user_profile');
        }
        return $this->render('admin/user/profile/updatePassword.html.twig',[
            'form' => $form->createView()
        ]);
    }
}