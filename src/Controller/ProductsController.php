<?php

namespace App\Controller;

use App\Entity\Products;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


#[Route('/products', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository): Response
    {

        $product = $productsRepository->findAll();
        
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
            'products' => $product
        ]);
    }

/**
 * ----------------------------------------------------------------------------------
 */
//      #[Route('/{slug}', name: 'category', priority : 1 )]
    
//      public function category(CategoryRepository $categoryRepository, $slug): Response
//      {
//         $category = $categoriesRepository->findOneBySlug($slug);

//         if (!$category) {
//             throw $this->createNotFoundException("La categorie demandé n'existe pas");
//         }


//         return $this->render('products/category.html.twig', [
//             'controller_name' => 'ProductsController',
            
//               'category' =>  $category,
//         ]);
//     }
/**
 * -------------------------------------------------------------------------------------------
 */
    #[Route('/{id}', name: 'app_show')]
    public function show(Products $products): Response
    {
        return $this->render('products/show.html.twig', [
            'product' => $products
        ]);
    }

/**
 * ------------------------------------------------------------------------------------------
 */

    #[Route('/new', name: 'new')]
    public function new(): Response
    {
       
        return $this->render('products/new.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }
}
