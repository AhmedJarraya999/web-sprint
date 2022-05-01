<?php

namespace App\Controller;

use App\Entity\Stay;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="app_profile")
     */
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
    /**
     * @Route("/update", name="update_profile")
     */

    public function profile(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($plainPassword = $request->request->get('plainPassword')) {
                $hash = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($hash);
            }

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les données du profil ont été enregistrée avec succès !"
            );
            return $this->redirectToRoute('account_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @Route("/account", name="account_index")
     * 
     * @return Response
     */
    public function myAccount()
    {
        return $this->render('profile/myprof.html.twig', [
            'user' => $this->getUser()
        ]);
    }
    /**
     * Permet d'afficher le profil du host de  cette stay
     *
     * @Route("/accounthost/{stay}", name="accounthoststay")
     * 
     * @return Response
     */
    public function AccountHost(Stay $stay)
    {
        return $this->render('profile/myprof.html.twig', [
            'user' => $stay->getUsers()
        ]);
    }
}
