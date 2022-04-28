<?php

namespace App\Controller;


use Twilio\Rest\Client;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder,
        MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $sid    = "AC0590a507c82a4dedf7e2b59eeb81baf7";
            $token  = "03a962dc2346d5266946e3de591776f8";
            $twilio = new Client($sid, $token);
            $test = $user->getPhone();

            $email = (new TemplatedEmail())
                ->from(new Address('mailersendj1@gmail.com', 'Trips.com'))
                ->to($user->getEmail())
                ->subject('Trips.com account is active')
                ->htmlTemplate('emails/registration.html.twig')
                ->context([
                    'username' => $user->getUsername()
                ])
            ;

            $mailer->send($email);

            try {
                $message = $twilio->messages
                ->create(
                    $test, // to 
                    array(
                        "messagingServiceSid" => "MG6bab091d610907bb2021edc85f426141",
                        "body" => " You have succeffully registered to our platform"
                    )
                );
                
            }catch(Exception $ex){
                
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
