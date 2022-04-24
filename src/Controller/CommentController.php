<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Experience;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/Front/new", name="app_comment_new_front", methods={"GET", "POST"})
     */
    public function newFront(Request $request, EntityManagerInterface $entityManager ): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        //$user=$this->getDoctrine()->getRepository(User::Class)->find($id_user);
        //$experience->getDoctrine()->getRepository(Experience::class)->find($id_experience);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor(1);
            $comment->setIdExp(3);
            $date = date('d-m-y h:i');
            $comment->setDate($date);
            $comment->setLikes(0);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('experience/Front/19', [], Response::HTTP_SEE_OTHER);
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
}
