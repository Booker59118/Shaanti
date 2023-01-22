<?php

namespace App\Controller;

use App\Entity\Products;

use App\Repository\CategoriesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
   
     #[Route('/{slug}', name: 'list')]
    
    public function show(CategoriesRepository $categoriesRepository, $slug): Response
    {
        $category = $categoriesRepository->findOneBySlug($slug);

        if (!$category) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas");
        }
        $products =$category->getProducts();

        return $this->render('categories/list.html.twig', [
            'controller_name' => 'CategoriesController',
            
               'category' => $category,
               'products'=>$products
        ]);
    }

   
}
