<?php

namespace App\Controller;

use App\Model\SearchData;
use App\Form\SearchType;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository ,Request $request): Response
    {
        $products = $productsRepository->findBy([], [], 3);

      


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'categories' => $categoriesRepository->findBy([],
            ['categoryOrder' => 'asc']    /*l'ordre de mes categories sera ascendant */
            )
        ]);
    }

    #[Route('condition', name: 'app_condition')]
    public function condition(): Response
    {
        return $this->render('condition/index.html.twig', [
            
        ]);
    }
    #[Route('legal_notice', name: 'app_legalnotice')]
     public function legal_notice(): Response
     {
         return $this->render('legal_notice/index.html.twig',[

         ]);
     }
    #[Route('About', name: 'app_about')]
     public function about(): Response
     {
        return $this->render('about/index.html.twig',[

        ]);
     }
}

