<?php

namespace App\Controller;

use App\Entity\User;

use App\Service\JWTService;
use App\Service\SendMailService;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,
     UserAuthenticatorInterface $userAuthenticator, UserAuthenticator $authenticator,
      EntityManagerInterface $entityManager, SendMailService $mail, JWTService $jwt): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

             $entityManager->persist($user);
             $entityManager->flush();
            // do anything else you need here, like send an email

            //On génère le JWT de l'utilisateur
            //On crée le header
            $header = [
                
                'alg'=> 'HS256',
                'typ'=> 'JWT'
            ];

            //on crée le payload
            $payload = [
                'user_id' => $user->getId()
            ];

//             //on génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
           
           
            //on envoie un mail
            $mail->send(
                'no-reply@Shaanti.fr',
                $user->getEmail(),
                'Activation de votre compte sur le site Shaanti',
                'register',compact('user', 'token')
               
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }



    #[Route('/verif/{token}', name:'verify_user')]
    public function verifyUser($token, JWTService $jwt, UserRepository $userRepository,
     EntityManagerInterface $em): Response
    { 

        //on verifie si le lien est valide, n'a pas expiré et pas été modifié
        if($this->$jwt->isValid($token) && !$this->$jwt->isExpired($token) && $this->$jwt->check($token, $this->getParameter('app.jwtsecret'))){
            //on récupère le payload
            $payload =$this-> $jwt->getPayload($token);
            
            //on récupère le user du token
            $user =$this-> $userRepository->find($payload['user_id']);

            //on verifie que l'utilisateur existe et n'a pas encore activé son compte
            if ($user && !$user->getIsVerified()) {
                $user->setIsVerified(true);
                $this->$em->flush($user);
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('app_login');
            }
        }
        //Ici un problème se pose dans le token
        $this->addFlash('danger', 'Le token est invalide ou a
        expiré');
        return $this->redirectToRoute('app_login');
    }


      

    #[Route('renvoiverif', name: 'resend_verif')]
    public function resendVerif(JWTService $jwt, SendMailService $mail,
     UserRepository $userRepository): Response
    {
        $user =$this->getUser();

        if (!$user){
            $this->addFlash('danger', 'Vous devez être connecté pour acceder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()){
            $this->addFlash('warning', 'Cet utilisateur est déjà activé');
            return $this->redirectToRoute('profile_index');    
    }
        // On génère le JWT de l'utilisateur
        // On crée le Header
        $header = [
            'alg'=> 'HS256',
            'typ'=> 'JWT'
        ];
        
        //on crée le payload
        $payload = [
            'user_id' => $user->getId()
        ];

//             //on génère le token
        $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));
       
       
        //on envoie un mail
        $mail->send(
            'no-reply@monsite.net',
            $user->getEmail(),
            'Activation de votre compte sur le site Shaanti',
            'register',
            [
                'user' => $user,
                'token'=>$token
            ]
        );
        $this->addFlash('success', 'Email de vérification envoyé');
        return $this->redirectToRoute('profile_index');


    }
}
    