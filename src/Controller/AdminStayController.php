<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StayRepository;
use App\Entity\Stay;
use App\Form\StayType;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

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
    /**
     * @Route("/{id}/admin/stay/edit", name="app_admin_stay_edit" , methods={"GET", "POST"})
     */
    public function edit(StayRepository $repo, Stay $stay, Request $request): Response
    {
        $form = $this->createForm(StayType::class, $stay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            $uploads_directory = $this->getParameter('kernel.project_dir') . '/public/uploads';
            $filename = md5(uniqid()) . '.' . $photo->guessExtension();
            $photo->move(
                $uploads_directory,
                $filename
            );
            $stay->setPhoto($filename);

            $repo->add($stay);
            return $this->redirectToRoute('app_admin_stay', [], Response::HTTP_SEE_OTHER);
        }
    }
}
