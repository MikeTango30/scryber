<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    public function register(\Swift_Mailer $mailer, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setCredits(1800);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $userName = $user->getFirstname();
            $userEmail = $user->getEmail();

            $message = (new \Swift_Message('Scriber'))
                ->setFrom('scriber.assistant@gmail.com')
                ->setTo($userEmail)
                ->setBody($this->renderView('emails/registered.html.twig', [
                        'name' => $userName
                    ]
                ),
                    'text/html'
                );
            $mailer->send($message);

            return $this->redirectToRoute('secure_login');

            //authenticate on registration
//            return $guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $authenticator,
//                'main' // firewall name in security.yaml
//            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
