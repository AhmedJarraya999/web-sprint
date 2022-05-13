<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Experience;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/Front", name="app_comment_index_front", methods={"GET"})
     */
    public function indexFront(EntityManagerInterface $entityManager): Response
    {
        $comments = $entityManager
            ->getRepository(Comment::class)
            ->findAll();

        return $this->render('Front-office/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/Front/new/{id}", name="app_comment_new_front", methods={"GET", "POST"})
     */
    public function newFront(Request $request, CommentRepository $commentRepository ,Experience $id ): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            # tekhou l'id taa el user li mconnecti
            $author = $this->getUser();
            # taffecti el id taa el user lemconnecti lel attribut user fel class experience
            $comment->setAuthor(1);

            $date = date('d-m-y h:i');
            $comment->setDate($date);
            #taffecti 0 par defaut lel attribut likes fel classe comment
            $comment->setLikes(0);
            #bech tekhou l'id taa el experience eli fel route w  taffectih lel attribut experience fel classe commentaire
           # $comment->setExperience($id);
            $comment->setIdExp($id);
            $commentRepository->add($comment);

            return $this->redirectToRoute('experience/Front/{id}', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Front-office/comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Front/{id}", name="app_comment_show_front", methods={"GET"})
     */
    public function showFront(Comment $comment): Response
    {
        return $this->render('Front-office/comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/Front/{id}/edit", name="app_comment_edit_front", methods={"GET", "POST"})
     */
    public function editFront(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Front-office/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Front/{id}", name="app_comment_delete_front", methods={"POST"})
     */
    public function deleteFront(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comment_index_front_front', [], Response::HTTP_SEE_OTHER);
    }








    /**
     * @Route("/Back", name="app_comment_index_back", methods={"GET"})
     */
    public function indexBack(EntityManagerInterface $entityManager): Response
    {
        $comments = $entityManager
            ->getRepository(Comment::class)
            ->findAll();

        return $this->render('Back-office/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/Back/new", name="app_comment_new_back", methods={"GET", "POST"})
     */
    public function newBack(Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back-office/comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Back/{id}", name="app_comment_show_back", methods={"GET"})
     */
    public function showBack(Comment $comment): Response
    {
        return $this->render('Back-office/comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    /**
     * @Route("/Back/{id}/edit", name="app_comment_edit_back", methods={"GET", "POST"})
     */
    public function editBack(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index_back', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('Back-office/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Back/{id}", name="app_comment_delete_back", methods={"POST"})
     */
    public function deleteBack(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comment_index_back', [], Response::HTTP_SEE_OTHER);
    }



    /**
     * @Route("/sayebni/{content}", name="app_comment_searchByContent", methods={"GET"})
     */
    public function findByContent($content) :Response
    {
        $rep=$this->getDoctrine()->getRepository(Comment::class);
        $response = new JsonResponse();
        if ($content != "") {
            $comments = $rep->searchComment($content);
            $response->setData(($comments));
        } else {
            $response->setData([]);
        }
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("/searchAll", name="app_comment_searchAll", methods={"GET"})
     */
    public function finAllComments(Request $request):Response
    {
        $rep = $this->getDoctrine()->getRepository(Comment::class);
        $comments = $rep->searchAllComments();
        $response = new JsonResponse();
        $response->setData($comments);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
