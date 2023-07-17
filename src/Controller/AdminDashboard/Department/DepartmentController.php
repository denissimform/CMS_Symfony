<?php

namespace App\Controller\AdminDashboard\Department;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/dashboard/department')]
class DepartmentController extends AbstractController
{
    public function __construct(private DepartmentRepository $departmentRepository){

    } 
    #[Route('/create', name: 'app_admin_department_create')]
    public function CreateDepartment(EntityManagerInterface $entityManagerInterface, Request $request): Response
    {
        $form = $this->createForm(DepartmentType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $department = new Department();
            $department = $form->getData();
            $entityManagerInterface->persist($department);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_admin_department');
        }

        return $this->render('AdminDashboard/Department/create_department.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('', name: 'app_admin_department')]
    public function Departments(DepartmentRepository $departmentRepository): Response
    {
        return $this->render('AdminDashboard/Department/index.html.twig');

        // return $this->render('AdminDashboard/Department/departments.html.twig',[
        //     'departments' => $departmentRepository->findBy([
        //         'isDeleted' => false
        //     ])
        // ]);
    }

    #[Route('/update/{id}', name: 'app_admin_department_update')]
    public function DepartmentUpdate(EntityManagerInterface $entityManagerInterface, Department $department, Request $request): Response
    {
        $form = $this->createForm(DepartmentType::class,$department);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $department = $form->getData();
            $entityManagerInterface->flush();

            return $this->redirectToRoute('app_admin_department');
        }
        return $this->render('AdminDashboard/Department/create_department.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/updateStatus/{id}', name: 'app_admin_department_update_status')]
    public function DepartmentUpdateStatus(EntityManagerInterface $entityManagerInterface, Department $department): Response
    {
        $department->setIsActive($department->isIsActive()^true);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('app_admin_department');
    }

    #[Route('/delete/{id}', name: 'app_admin_department_delete')]
    public function DepartmentDelete(EntityManagerInterface $entityManagerInterface, Department $department): Response
    {
        $department->setIsDeleted(true);
        $entityManagerInterface->flush();

        return $this->redirectToRoute('app_admin_department');
    }

    #[Route('/datatable', name: 'app_admin_department_dt')]
    public function adminDatatable(Request $request): Response
    {
        $requestData = $request->query->all();

        $orderByField = $requestData['columns'][$requestData['order'][0]['column']]['data'];
        $orderDirection = $requestData['order'][0]['dir'];
        $searchBy = $requestData['search']['value'] ?? null;

        $departments = $this->departmentRepository->dynamicDataAjaxVise($requestData['length'], $requestData['start'], $orderByField, $orderDirection, $searchBy);
        $totalUsers = $this->departmentRepository->getTotalUsersCount();
        
        $response = [
            "data" => $departments,
            "recordsTotal" => $totalUsers,
            "recordsFiltered" => $totalUsers
        ];

        return $this->json($response);
    }
}
