<?php

namespace App\Controller\Admin;

use App\Entity\Skills;
use App\Form\SkillType;
use App\Repository\SkillsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Context\Encoder\CsvEncoderContextBuilder;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

#[Route('/admin/skills')]
class SkillsController extends AbstractController
{
    public function __construct(
        private SkillsRepository $skillsRepository
    ) {
    }

    #[Route('', name: 'app_admin_skills')]
    public function Skills(): Response
    {
        return $this->render('admin/skills/index.html.twig');
        // return $this->render('Admin/Skills/skills.html.twig',[
        //     'skills' => $skillsRepository->findBy([
        //         'isDeleted' => false
        //     ]) 
        // ]);
    }

    #[Route('/create', name: 'app_admin_skills_create')]
    public function CreateSkill(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $form = $this->createForm(SkillType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Skills $skill */
            $skill = $form->getData();
            $skill->setCreatedBy($this->getUser());

            $entityManagerInterface->persist($skill);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_admin_skills');
        }

        return $this->render('admin/skills/create_skill.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'app_admin_skills_update')]
    public function UpdateSkill(EntityManagerInterface $entityManagerInterface, Skills $skill, Request $request): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $skill = $form->getData();
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_admin_skills');
        }
        return $this->render('admin/skills/create_skill.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/updateStatus/{id}', name: 'app_admin_skills_update_status')]
    public function UpdateSkillStatus(EntityManagerInterface $entityManagerInterface, Skills $skills): Response
    {
        $skills->setIsActive($skills->isIsActive() ^ true);
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

        $initialContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 100,
        ];

        $contextBuilder = (new ObjectNormalizerContextBuilder())
            ->withContext($initialContext);

        dd($this->json(
            $response,
            context: $contextBuilder->toArray()
            // ['groups' => [
            // AbstractNormalizer::CIRCULAR_REFERENCE_LIMIT => 20
            // 'skills:dt:read',
            // 'user:dt:read',
            // ]]
        ));

        return $this->json($response, context: ['groups' => [
            'skills:dt:read',
            'user:dt:read',
        ]]);
    }

    #[Route('/timeline', name: 'timeline')]
    public function timeline(Request $request): Response
    {
        $form = $this->createForm(SkillType::class);
        return $this->render('Admin/Timeline/timeline.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
