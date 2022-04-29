<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StayRepository;

class AdminStayController extends AbstractController
{
    /**
     * @Route("/admin/stay", name="app_admin_stay")
     */
    public function index(StayRepository $repo): Response
    {
        return $this->render('admin_stay/index.html.twig', [
            'controller_name' => 'AdminStayController',
            'stays' => $repo->findAll()
        ]);
    }
}
