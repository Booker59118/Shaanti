<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_show')]
    public function show()
    {

    

      

        return $this->render('cart/show.html.twig', [
        'controller_name' => 'CartController']);
           
    }




     #[Route('/cart/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'])]
     public function add($id, ProductsRepository $productsRepository, SessionInterface $session)
     {  
        
        //0. Securisation: est ce que le produit existe ?
        $product = $productsRepository->find($id);

        if(!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }
        
       //1. retrouver le panier dans la session (sous forme de tableau) 

       //2.  si il n'existe pas encore, prendre le tableau vide
       $cart = $session->get('cart', []);

       //3. voir si le produit ($id) existe déjà dans le tableau
       if(array_key_exists($id, $cart)){
            $cart[$id]++;
       }
       else{
        $cart[$id] = 1;
       }

       //4. si il n'existe pas, augmenter la qty

       //5. sinon ajouter le produit avec la quantité 1

       //6. enregistrer le tableau mis à jour dans la session
       $session->set('cart', $cart);


       /** @var FlashBag */
       $flashBag = $session->getBag('flashes');

       $flashBag->add('success', "Le produit a bien été ajouté au panier");
      

         return $this->redirectToRoute('products_app_show',[
            'category_slug' => $product->getCategories()->getSlug(),
            'slug' => $product->getSlug(),
            'id' => $product->getId()
            
         ]);
     }

    
}
