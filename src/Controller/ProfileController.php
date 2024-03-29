<?php

namespace App\Controller;

use App\Service\JWTService;
use App\Service\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profil de l\'utilisateur',
        ]);
    }

    #[Route('/commandes', name: 'orders')]
    public function orders(): Response
    {
        

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Commandes de l\'utilisateur',
        ]);
    }

    #[Route('/modifier', name: 'modifier')]
    public function modifier(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Commandes de l\'utilisateur',
        ]);
    }

   
    }

