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
    public function CreateSkill(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $form = $this->createForm(SkillType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $skill = new Skills();
            $skill = $form->getData();
            $skill->setIsActive(false);
            $skill->setIsDeleted(false);
            $skill->setCreatedAt(new \DateTime());
            $skill->setUpdatedAt(new \DateTime());
            $entityManagerInterface->persist($skill);
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute('app_admin_skills');
        }

        return $this->render('AdminDashboard/Skills/create_skill.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/dashboard/skills', name: 'app_admin_skills')]
    public function Skills(EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('AdminDashboard/Skills/skills.html.twig',[
            'skills' => $entityManagerInterface->getRepository(Skills::class)->findBy([
                'isDeleted' => false
            ]) 
        ]);
    }

    #[Route('/admin/dashboard/skills/update/{id}/{flag}', name: 'app_admin_skills_update')]
    public function Update_Skill(EntityManagerInterface $entityManagerInterface, Skills $skill, Request $request, int $flag): Response
    {
        if($flag==1){
            $form = $this->createForm(SkillType::class,$skill);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $skill = $form->getData();
                $skill->setUpdatedAt(new \DateTime());
                $entityManagerInterface->flush();

                return $this->redirectToRoute('app_admin_skills');
            }
            return $this->render('AdminDashboard/Skills/create_skill.html.twig',[
                'form' => $form->createView()
            ]);
        } else {
            $skill->setIsActive($skill->isIsActive()^true);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_admin_skills');
        }
        
    }

    #[Route('/admin/dashboard/skills/delete/{id}', name: 'app_admin_skills_delete')]
    public function Delete_Skill(EntityManagerInterface $entityManagerInterface, Skills $skill): Response
    {
        $skill->setIsDeleted(true);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_admin_skills');
    }

    /******************************************DEPARTMENT***************************************************/

    #[Route('/admin/dashboard/department/create', name: 'app_admin_department_create')]
    public function CreateDepartment(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $form = $this->createForm(DepartmentType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $department = new Department();
            $department = $form->getData();
            $department->setIsActive(false);
            $department->setIsDeleted(false);
            $department->setCreatedAt(new \DateTime());
            $department->setUpdatedAt(new \DateTime());
            $entityManagerInterface->persist($department);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_admin_department');
        }

        return $this->render('AdminDashboard/Department/create_department.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/dashboard/department', name: 'app_admin_department')]
    public function Departments(EntityManagerInterface $entityManagerInterface): Response
    {
        return $this->render('AdminDashboard/Department/departments.html.twig',[
            'departments' => $entityManagerInterface->getRepository(Department::class)->findBy([
                'isDeleted' => false
            ])
        ]);
    }

    #[Route('/admin/dashboard/department/update/{id}/{flag}', name: 'app_admin_department_update')]
    public function DepartmentUpdate(EntityManagerInterface $entityManagerInterface, Department $department, Request $request, int $flag): Response
    {
        if($flag==1) {
            $form = $this->createForm(DepartmentType::class,$department);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $department = $form->getData();
                $department->setUpdatedAt(new \DateTime());
                $entityManagerInterface->flush();

                return $this->redirectToRoute('app_admin_department');
            }
            return $this->render('AdminDashboard/Department/create_department.html.twig',[
                'form' => $form->createView()
            ]);
        } else {
            $department->setIsActive($department->isIsActive()^true);
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_admin_department');
        }
        
    }

    #[Route('/admin/dashboard/department/delete/{id}', name: 'app_admin_department_delete')]
    public function DepartmentDelete(EntityManagerInterface $entityManagerInterface, Department $department): Response
    {
        $department->setIsDeleted(true);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('app_admin_department');
    }
}
