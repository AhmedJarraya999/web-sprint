<?php

namespace App\Controller;

use App\Entity\Stay;
use App\Form\StayType;
use App\Repository\StayRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stay")
 */
class StayController extends AbstractController
{
    /**
     * @Route("/", name="app_stay_index", methods={"GET"})
     */
    public function index(StayRepository $stayRepository): Response
    {
        return $this->render('stay/index.html.twig', [
            'stays' => $stayRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_stay_new", methods={"GET", "POST"})
     */
    public function new(Request $request, StayRepository $stayRepository): Response
    {
        $stay = new Stay();
        $form = $this->createForm(StayType::class, $stay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stayRepository->add($stay);
            return $this->redirectToRoute('app_stay_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stay/new.html.twig', [
            'stay' => $stay,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_stay_show", methods={"GET"})
     */
    public function show(Stay $stay): Response
    {
        return $this->render('stay/show.html.twig', [
            'stay' => $stay,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_stay_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Stay $stay, StayRepository $stayRepository): Response
    {
        $form = $this->createForm(StayType::class, $stay);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stayRepository->add($stay);
            return $this->redirectToRoute('app_stay_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stay/edit.html.twig', [
            'stay' => $stay,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_stay_delete", methods={"POST"})
     */
    public function delete(Request $request, Stay $stay, StayRepository $stayRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stay->getId(), $request->request->get('_token'))) {
            $stayRepository->remove($stay);
        }

        return $this->redirectToRoute('app_stay_index', [], Response::HTTP_SEE_OTHER);
    }
}
