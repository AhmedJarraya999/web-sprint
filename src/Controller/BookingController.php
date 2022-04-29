<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Stay;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

/**
 * @Route("/booking")
 */
class BookingController extends AbstractController
{
    /**
     * @Route("/", name="app_booking_index", methods={"GET"})
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="MyBookings", methods={"GET"})
     */
    public function MyBookings(BookingRepository $bookingRepository): Response
    {
        //todo implement a function in the booking repository that lists the booking where booking.user.id is equal to the id of the user conneceted

        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }


    /**
     * @Route("/print", name="printBooking")
     */
    public function ImprimerCommande()
    {
        $repository = $this->getDoctrine()->getRepository(Booking::class);
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $commande = $repository->findAll();


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView(
            'booking/show.html.twig',
            ['booking' => $commande]
        );

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Commande_finale.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/{stay}/book", name="app_booking_stay", methods={"GET", "POST"})
     */
    public function create(
        Request $request,
        Stay $stay,
        BookingRepository $bookingRepository,
        MailerInterface $mailer
    ): Response {
        $booking = new Booking();
        $booking->setStay($stay);
        $booking->setBookingDate(new DateTime());


        $user = $this->getUser();
        $booking->setUser($user);

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $booking->setUser($user);
            $bookingRepository->add($booking);
            // Instantiate Dompdf with our options
            $dompdf = new Dompdf();
            $dompdf->setPaper('A4', 'portrait');

            // Retrieve the HTML generated in our twig file
            $html = $this->renderView(
                // templates/emails/registration.html.twig
                'emails/booking.html.twig',
                ['stay' => $booking->getStay(), 'booking' => $booking, 'user' => $user]
            );

            // Load HTML to Dompdf
            $dompdf->loadHtml($html);
            // Render the HTML as PDF
            $dompdf->render();

            $email = (new TemplatedEmail())
                ->from(new Address('mailersendj1@gmail.com', 'Trips.com'))
                ->to($booking->getStay()->getUsers()->getEmail())
                #$user->getEmail()
                ->subject('Your booking at Trips.com saved successfully')
                ->htmlTemplate('emails/booking.html.twig')
                ->context([
                    'stay' => $booking->getStay(),
                    'booking' => $booking,
                    'user' => $user
                ]);

            $mailer->send($email);

            // Output the generated PDF to Browser (force download)
            $dompdf->stream("booking.pdf", [
                "Attachment" => true
            ]);
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/new", name="app_booking_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BookingRepository $bookingRepository): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->add($booking);
            return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
            # todo this should redirect to  mybookings page
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_booking_show", methods={"GET"})
     */
    public function show(Booking $booking): Response
    {
        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_booking_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingRepository->add($booking);
            return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_booking_delete", methods={"POST"})
     */
    public function delete(Request $request, Booking $booking, BookingRepository $bookingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
            $bookingRepository->remove($booking);
        }

        return $this->redirectToRoute('app_booking_index', [], Response::HTTP_SEE_OTHER);
    }
}
