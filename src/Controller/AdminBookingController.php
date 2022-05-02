<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BookingRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

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
    /**
     * @Route("/{id}/admin/booking/edit", name="app_admin_booking_edit" , methods={"GET", "POST"})
     */
    public function edit(BookingRepository $repo, Booking $booking, Request $request, BookingRepository $rep): Response
    {
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $rep->add($booking);

            $this->addFlash('success', "la reservation n° {$booking->getId()} a eté bien modifiée");
            return $this->redirectToRoute("app_admin_booking");
        }

        return $this->render('admin_booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);
    }

    /**     
     * @Route("/{id}/admin/booking/delete", name="app_admin_booking_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Booking $booking, BookingRepository $rep,  ManagerRegistry $managerRegistry): Response
    {

        $em = $managerRegistry->getManager();
        $em->remove($booking);
        $em->flush();
        $this->addFlash(
            'Success',
            "Stay with id <strong>{$booking->getId()}</strong> was succeffully deleted ! "
        );


        return $this->redirectToRoute('app_admin_booking', [], Response::HTTP_SEE_OTHER);
    }
}
