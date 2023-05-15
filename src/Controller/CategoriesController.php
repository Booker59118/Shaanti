<?php

namespace App\Controller;

use App\Entity\Products;

use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
   
     #[Route('/{slug}', name: 'list')]
    
    public function show(CategoriesRepository $categoriesRepository, $slug, ProductsRepository $productsRepository, Request $request): Response
    {
        $category = $categoriesRepository->findOneBySlug($slug);
        

        if (!$category) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas");
        }
        // on va chercher le numéro de page dans l'url
        $page = $request->query->getInt('page', 1);



        //on va chercher la liste des produits de la catégorie
        $products =$productsRepository->findProductsPaginated($page, $category->getSlug(), 3);
      

      

        return $this->render('categories/list.html.twig', [
            'controller_name' => 'CategoriesController',
            
               'category' => $category,
               'products'=>$products,
              
             

             
               
        ]);
    }


   
}
