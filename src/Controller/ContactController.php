<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(MailerInterface $mailer, Request $request, EntityManagerInterface $em): Response
    {
      $contact = new Contact();

      if($this->getUser()){
        $contact->setEmail($this->getUser()->getEmail())
                ->setFirstName($this->getUser()->getFirstName())
                ->setLastName($this->getUser()->getLastName());
      }
       
      $form = $this->createForm(ContactType::class, $contact);     
      
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()){
        $contact = $form->getData();
      
        $em->persist($contact);

        $em->flush();

        $this->addFlash('success', 'Message bien envoyé');
    
        
            $email = (new Email())
                ->from($contact->getEmail())
                ->to('admin@Shaanti.com')
                ->subject($contact->getSubject())
                ->html($contact->getMessage());
    
            $mailer->send($email);
    
      }   
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'form' => $form->createView(),
        ]);
    }
}
