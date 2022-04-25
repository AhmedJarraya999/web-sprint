<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/admin")
 */
class AdminAccountController extends AbstractController
{
    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @Route("/profile", name="admin_profile")
     * 
     * @return Response
     */
    public function profile(Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($plainPassword = $request->request->get('plainPassword')) {
                $hash = $encoder->encodePassword($user, $plainPassword);
                $user->setPassword($hash);
            }
            
            $userRepository->add($user);
            $this->addFlash('success', 'Profile settings saved successfully!');
            return $this->redirectToRoute('admin_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_account/profile.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/login", name="admin_account_login")
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin_account/index.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se déconnecter
     * 
     * @Route("/admin/logout", name="admin_account_logout")
     *
     * @return void
     */
    public function logout()
    {
        // ...
    }
}
