<?php

namespace App\Controller\AdminDashboard;

use App\Entity\Department;
use App\Entity\Skills;
use App\Form\DepartmentType;
use App\Form\SkillType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    #[Route('/admin/dashboard', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('AdminDashboard/AdminDashboard.html.twig');
    }

    /******************************************SKILLS***************************************************/

    #[Route('/admin/dashboard/skills/create', name: 'app_admin_skills_create')]
    public function CreateSkill(EntityManagerInterface $entityManagerInterface, Skills $skill, Request $request): Response
    {
        $form = $this->createForm(SkillType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $skill = $form->getData();
            dd($skill);
            $entityManagerInterface->persist($skill);
        }

        return $this->render('AdminDashboard/Skills/create_skill.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/dashboard/skills', name: 'app_admin_skills')]
    public function Skills(EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('AdminDashboard/Skills/skills.html.twig',[
            'skills' => $entityManagerInterface->getRepository(Skills::class)->findAll() 
        ]);
    }

    #[Route('/admin/dashboard/skills/update/{id}', name: 'app_admin_skills_update')]
    public function Update_Skill(EntityManagerInterface $entityManagerInterface, Skills $skill): Response
    {
        $form = $this->createForm(SkillType::class,$skill);
        if($form->isSubmitted() && $form->isValid()){
            $entityManagerInterface->persist($skill);
            return $this->redirectToRoute('app_admin_skills');
        }
        return $this->render('AdminDashboard/Skills/create_skill.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/dashboard/skills/delete/{id}', name: 'app_admin_skills_delete')]
    public function Delete_Skill(EntityManagerInterface $entityManagerInterface, Skills $skill): Response
    {
        $entityManagerInterface->remove($skill);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_admin_skills');
    }

    /******************************************DEPARTMENT***************************************************/

    #[Route('/admin/dashboard/department/create', name: 'app_admin_department_create')]
    public function CreateDepartment(): Response
    {
        $form = $this->createForm(DepartmentType::class);
        return $this->render('AdminDashboard/Department/create_department.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/dashboard/department', name: 'app_admin_department')]
    public function Departments(EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('AdminDashboard/Department/departments.html.twig',[
            'departments' => $entityManagerInterface->getRepository(Department::class)->findAll() 
        ]);
    }

    #[Route('/admin/dashboard/department/update/{id}', name: 'app_admin_department_update')]
    public function DepartmentUpdate(EntityManagerInterface $entityManagerInterface, Department $department): Response
    {
        $form = $this->createForm(DepartmentType::class,$department);
        return $this->render('AdminDashboard/Department/create_department.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/dashboard/department/delete/{id}', name: 'app_admin_department_delete')]
    public function DepartmentDelete(EntityManagerInterface $entityManagerInterface, Department $department): Response
    {
        $entityManagerInterface->remove($department);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_admin_department');
    }
}
