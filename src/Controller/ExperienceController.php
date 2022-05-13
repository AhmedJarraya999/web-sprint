<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Entity\Comment;

use App\Repository\ExperienceRepository;
use App\Form\ExperienceType;
use App\Form\CommentType;

use App\Form\SearchExperienceType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

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

        return $this->render('Front-office/experience/index.html.twig', [
            'experiences' => $experiences,
        ]);
    }

    /**
     * @Route("/Front/new", name="app_experience_new_front", methods={"GET", "POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function newFront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            #tekhou l'id mtaaa el user elmconnecti
            $user = $this->getUser();
            #taffectih lel attribute user fel classe experience (author)
            $experience->setIdAuthor(1);

            $experience->setLikes(0);
            $date = date('d-m-y h:i');
            $experience->setDate($date);
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
    public function showFront(Request $request ,Experience $id, EntityManagerInterface $entityManager ): Response
    {   $experience = $this->getDoctrine()->getRepository(Experience::class)->find($id);
        $comments= $this->getDoctrine()->getRepository(Comment::class)->listCommentByExperience($experience->getId());




        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        //$user=$this->getDoctrine()->getRepository(User::Class)->find($id_user);
        //$experience->getDoctrine()->getRepository(Experience::class)->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor(1);
            $comment->setIdExp($id);
            $date = date('d-m-y h:i');
            $comment->setDate($date);
            $comment->setLikes(0);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('experience/Front/{id}', [], Response::HTTP_SEE_OTHER);
        }



        return $this->render('Front-office/experience/show.html.twig', [
            'comment' => $comment,
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

    /**
     * @Route("/sayebni/{title}", name="app_experience_searchByTitle", methods={"GET"})
     */
    public function findByTitle($title) :Response
    {
        $rep=$this->getDoctrine()->getRepository(Experience::class);
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
    public function finAllExperiences(Request $request):Response
    {
        $rep = $this->getDoctrine()->getRepository(Experience::class);
        $experiences = $rep->searchAllExperiences();
        $response = new JsonResponse();
        $response->setData($experiences);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /**
     * @Route("/AllExperiences", name="AllExperiences")
     */
    public function AllExperiences (NormalizerInterface $Normalizer)
    {
        $repository = $this -> getDoctrine()->getRepository(Experience::class);
        $experiences = $repository->findAll();

        $jsonContent = $Normalizer->normalize($experiences,'json',['groups'=>'post:read']);

        return $this ->render('Front-office/experience/Allexperiences.html.twig',[
        ]);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/viewJSON/{id}", name="viewJSON")
     */
    public function viewJSON (Request $request,NormalizerInterface $Normalizer,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $experience=$em->getRepository(Experience::class)->find($id);
        $jsonContent = $Normalizer->normalize($experience,'json',['groups'=>'post:read']);
        return new Response((json_encode($jsonContent)));
    }
    /**
     * @Route("/addJSON/new", name="addJSON")
     */
    public function addJSON (Request $request,NormalizerInterface $Normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $experience=new Experience();
        $experience->setLikes($request->get('likes'));
        $experience->setDate($request->get('date'));
        $experience->setIdAuthor($request->get('idAuthor'));
        $experience->setContent($request->get('content'));
        $experience->setTitle($request->get('title'));

        $em->persist($experience);
        $em->flush();
        $jsonContent = $Normalizer->normalize($experience,'json',['groups'=>'post:read']);
        return new Response((json_encode($jsonContent)));
    }






}
