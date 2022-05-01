<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\Comment;

use App\Repository\ExperienceRepository;
use App\Form\ExperienceType;
use App\Form\CommentType;

use App\Form\SearchExperienceType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        return $this->render('experience/index.html.twig', [
            'experiences' => $experiences,
        ]);
    }

    /**
     * @Route("/Front/new", name="app_experience_new_front", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Security("is_granted('ROLE_HOST') || is_granted('ROLE_GUEST')")
     */
    public function newFront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);
        //$user=$this->getDoctrine()->getRepository(User::Class)->find($id_user);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser(); #tekhou l'id mtaaa el user elmconnecti
            $experience->setUser($user); #taffectih lel attribute user fel classe experience (author)
            $experience->setLikes(0); #nbr likes par defaut 0

            $experience->setDate(new DateTime()); #taati date actuelle lel attribut date fel classe experience
            $entityManager->persist($experience);
            $entityManager->flush();

            return $this->redirectToRoute('app_experience_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('experience/new.html.twig', [
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/FrontshowDetail/{id}", name="app_experience_show_front-details", methods={"GET"})
     */
    public function seeMore(Experience $id): Response
    {
        return $this->render('experience/show.html.twig', [
            'experience' => $id
        ]);
    }








    /**
     * @Route("/Front/{id}", name="app_experience_show_front", methods={"GET"})
     */
    public function showFront(Request $request, Experience $id, EntityManagerInterface $entityManager): Response
    {
        $experience = $this->getDoctrine()->getRepository(Experience::class)->find($id);




        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        //$user=$this->getDoctrine()->getRepository(User::Class)->find($id_user);
        //$experience->getDoctrine()->getRepository(Experience::class)->find($id_experience);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $comment->setUser($user);
            $comment->setUser($user);

            $comment->setDate(new DateTime());
            $comment->setLikes(0);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('experience/Front/3', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('experience/show.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),

            'experience' => $experience

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

        return $this->render('experience/edit.html.twig', [
            'experience' => $experience,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Front/{id}", name="app_experience_delete_front", methods={"POST"})
     */
    public function deleteFront(Request $request, Experience $experience, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $experience->getId(), $request->request->get('_token'))) {
            $entityManager->remove($experience);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_experience_index_front', [], Response::HTTP_SEE_OTHER);
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
        if ($this->isCsrfTokenValid('delete' . $experience->getId(), $request->request->get('_token'))) {
            $entityManager->remove($experience);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_experience_index_back', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/sayebni/{title}", name="app_experience_searchByTitle", methods={"GET"})
     */
    public function findByTitle($title): Response
    {
        $rep = $this->getDoctrine()->getRepository(Experience::class);
        $response = new JsonResponse();
        if ($title != "") {
            $experiences = $rep->searchExperience($title);
            $response->setData(($experiences));
        } else {
            $response->setData([]);
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/searchAll", name="app_experience_searchAll", methods={"GET"})
     */
    public function finAllExperiences(Request $request): Response
    {
        $rep = $this->getDoctrine()->getRepository(Experience::class);
        $experiences = $rep->searchAllExperiences();
        $response = new JsonResponse();
        $response->setData($experiences);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
