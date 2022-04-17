<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\Comment;

use App\Repository\ExperienceRepository;
use App\Form\ExperienceType;
use App\Form\SearchExperienceType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/experience")
 */
class ExperienceController extends AbstractController
{
    /**
     * @Route("/Front", name="app_experience_index_front", methods={"GET"})
     */
    public function indexFront(EntityManagerInterface $entityManager, Request $request): Response
    {
        $experiences = $entityManager
            ->getRepository(Experience::class)
            ->findAll();

            //search
        $searchForm = $this->createForm(SearchExperienceType::class);
        $searchForm->add("Search", SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $title = $searchForm['title']->getData();
            $resultOfSearch = $repository->searchExperience($title);
            return $this->render('Front-office/experience/index.html.twig', array(
                'resultOfSearch' => $resultOfSearch,
                'searchForm' => $searchForm->createView()));
        }

        return $this->render('Front-office/experience/index.html.twig', [
            'experiences' => $experiences,
        ]);
    }

    /**
     * @Route("/Front/new", name="app_experience_new_front", methods={"GET", "POST"})
     */
    public function newFront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($experience);
            $entityManager->flush();

            return $this->redirectToRoute('app_experience_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Front-office/experience/new.html.twig', [
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Front/{id}", name="app_experience_show_front", methods={"GET"})
     */
    public function showFront(Request $request ,Experience $id): Response
    {   $experience = $this->getDoctrine()->getRepository(Experience::class)->find($id);
        $comments= $this->getDoctrine()->getRepository(Comment::class)->listCommentByExperience($experience->getId());

        $commentnew = new Comment();
        $form = $this->createForm(CommentType::class, $commentnew);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->render('app_experience_show_front', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('Front-office/experience/show.html.twig', [
            'form' => $form->createView(),
            'experience' => $experience,
            'comments'=>$comments
        ]);
    }

    /**
     * @Route("/Front/{id}/edit", name="app_experience_edit_front", methods={"GET", "POST"})
     */
    public function editFront(Request $request, Experience $experience, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_experience_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Front-office/experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Front/{id}", name="app_experience_delete_front", methods={"POST"})
     */
    public function deleteFront(Request $request, Experience $experience, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$experience->getId(), $request->request->get('_token'))) {
            $entityManager->remove($experience);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_experience_index_front', [], Response::HTTP_SEE_OTHER);
    }


    
    /**
     * @Route("/Front/Search", name="app_experience_search_front", methods={"GET"})
     */
    public function listExperienceSearch(Request $request, ExperienceRepository $repository)
    {
        //All of experiences
        $experiences = $repository->findAll();
        
        //search
        $searchForm = $this->createForm(SearchExperienceType::class);
        $searchForm->add("Search", SubmitType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted()) {
            $title = $searchForm['title']->getData();
            $resultOfSearch = $repository->searchExperience($title);
            return $this->render('Front-office/experience/searchExperience.html.twig', array(
                'resultOfSearch' => $resultOfSearch,
                'searchExperience' => $searchForm->createView()));
        }
        return $this->render('Front-office/experience/index.html.twig', array(
            "experiences" => $experiences));
    } 








     /**
     * @Route("/Back", name="app_experience_index_back", methods={"GET"})
     */
    public function indexBack(EntityManagerInterface $entityManager): Response
    {
        $experiences = $entityManager
            ->getRepository(Experience::class)
            ->findAll();

        return $this->render('Back-office/experience/index.html.twig', [
            'experiences' => $experiences,
        ]);
    }

    /**
     * @Route("/Back/new", name="app_experience_new_back", methods={"GET", "POST"})
     */
    public function newBack(Request $request, EntityManagerInterface $entityManager): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($experience);
            $entityManager->flush();

            return $this->redirectToRoute('app_experience_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back-office/experience/new.html.twig', [
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Back/{id}", name="app_experience_show_back", methods={"GET"})
     */
    public function showBack(Experience $experience): Response
    {
        return $this->render('Back-office/experience/show.html.twig', [
            'experience' => $experience,
        ]);
    }

    /**
     * @Route("/Back/{id}/edit", name="app_experience_edit_back", methods={"GET", "POST"})
     */
    public function editBack(Request $request, Experience $experience, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_experience_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back-office/experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Back/{id}", name="app_experience_delete_back", methods={"POST"})
     */
    public function deleteBack(Request $request, Experience $experience, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$experience->getId(), $request->request->get('_token'))) {
            $entityManager->remove($experience);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_experience_index_back', [], Response::HTTP_SEE_OTHER);
    }
}
