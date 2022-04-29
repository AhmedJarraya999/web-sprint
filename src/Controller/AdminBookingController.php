<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookingRepository;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/booking", name="app_admin_booking")
     */
    public function index(BookingRepository $repo): Response
    {
        return $this->render('admin_booking/index.html.twig', [
            'controller_name' => 'AdminBookingController',
            'bookings' => $repo->findAll()
        ]);
    }
}
