<?php

namespace App\Controller\Admin\Skills;

use App\Entity\Skills;
use App\Form\SkillType;
use App\Repository\SkillsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/dashboard/skills')]
class SkillsController extends AbstractController
{
    public function __construct(
        private SkillsRepository $skillsRepository
    ){

    }
    #[Route('/create', name: 'app_admin_skills_create')]
    public function CreateSkill(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $form = $this->createForm(SkillType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $skill = new Skills();
            $skill = $form->getData();
            $entityManagerInterface->persist($skill);
            $entityManagerInterface->flush();
            
            return $this->redirectToRoute('app_admin_skills');
        }

        return $this->render('admin/skills/create_skill.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('', name: 'app_admin_skills')]
    public function Skills(SkillsRepository $skillsRepository): Response
    {
        return $this->render('admin/skills/index.html.twig');
        // return $this->render('admin/skills/skills.html.twig',[
        //     'skills' => $skillsRepository->findBy([
        //         'isDeleted' => false
        //     ]) 
        // ]);
    }

    #[Route('/update/{id}', name: 'app_admin_skills_update')]
    public function UpdateSkill(EntityManagerInterface $entityManagerInterface, Skills $skill, Request $request): Response
    {
        $form = $this->createForm(SkillType::class,$skill);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $skill = $form->getData();
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_admin_skills');
        }
        return $this->render('admin/skills/create_skill.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/updateStatus/{id}', name: 'app_admin_skills_update_status')]
    public function UpdateSkillStatus(EntityManagerInterface $entityManagerInterface, Skills $skills): Response
    {
        $skills->setIsActive($skills->isIsActive()^true);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_admin_skills');
    }
    
    #[Route('/delete/{id}', name: 'app_admin_skills_delete')]
    public function DeleteSkill(EntityManagerInterface $entityManagerInterface, Skills $skill): Response
    {
        $skill->setIsDeleted(true);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_admin_skills');
    }

    #[Route('/datatable', name: 'app_admin_skill_dt')]
    public function adminDatatable(Request $request): Response
    {
        $requestData = $request->query->all();

        $orderByField = $requestData['columns'][$requestData['order'][0]['column']]['data'];
        $orderDirection = $requestData['order'][0]['dir'];
        $searchBy = $requestData['search']['value'] ?? null;

        $skills = $this->skillsRepository->dynamicDataAjaxVise($requestData['length'], $requestData['start'], $orderByField, $orderDirection, $searchBy);
        $totalUsers = $this->skillsRepository->getTotalUsersCount();
        
        $response = [
            "data" => $skills,
            "recordsTotal" => $totalUsers,
            "recordsFiltered" => $totalUsers
        ];

        return $this->json($response);
    }

    #[Route('/timeline', name: 'timeline')]
    public function timeline(Request $request): Response
    {
        $form = $this->createForm(SkillType::class);
        return $this->render('Admin/Timeline/timeline.html.twig',[
            'form' => $form->createView()
        ]);

    }
}
