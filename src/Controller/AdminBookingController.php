<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/booking", name="app_admin_booking")
     */
    public function index(): Response
    {
        return $this->render('admin_booking/index.html.twig', [
            'controller_name' => 'AdminBookingController',
        ]);
    }
}
