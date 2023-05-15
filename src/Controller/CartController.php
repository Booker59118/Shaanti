<?php

namespace App\Controller;



use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_show')]
    public function show(ProductsRepository $productsRepository, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        
        //on fabrique les données
        $dataCart =[];
        $total = 0;

        // foreach($session->get('cart', []) as $id => $qty){$
            foreach($cart as $id => $qty){
            $product = $productsRepository->find($id);
            if(!$product){
                continue;
               }

            $dataCart[] =[
                'product' =>$product,
                'qty'=>$qty
            ];
           
            $total += ($product->getPrice() * $qty);
        }
    
        return $this->render('cart/show.html.twig', [
            'controller_name' => 'CartController',
            'items' => $dataCart,
            'total' => $total
        ]);
           
    }




     #[Route('/cart/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'])]
     public function add($id,  ProductsRepository $productsRepository, SessionInterface $session)
     {  
        $product = $productsRepository->find($id);

        if(!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas ! ");
        }

        

        $cart = $session->get('cart', []);
        // $id = $products->getID();
        
        if(!empty($cart[$id])){
            $cart[$id]++;
        }
        else{
            $cart[$id] = 1;
        }

        // on sauvegarde dans la session
        $session->set('cart', $cart);
        

    // 
       $this->addFlash('success', "Le produit a bien été ajouté au panier"); 
    //    $flashBag->add('success', "Le produit a bien été ajouté au panier");
      

         return $this->redirectToRoute('cart_show',[
            'id' => $id,
            'cart' => $cart,
           
            
           ]);
     }

     #[Route('/cart/remove/{id}', name: 'cart_remove', requirements: ['id' => '\d+'])]
     public function remove($id, SessionInterface $session, ProductsRepository $productsRepository)
     {  
        $cart = $session->get('cart', []);
        // $id = $products->getID();
        
        if(!empty($cart[$id])){
            if($cart[$id]>1){
            $cart[$id]--;
            }else{
                unset($cart[$id]);
            }
        }
      

        // on sauvegarde dans la session
        $session->set('cart', $cart);
        

    //    /** @var FlashBag */
    //    $flashBag = $session->getBag('flashes');

    //    $flashBag->add('success', "Le produit a bien été ajouté au panier");
      

         return $this->redirectToRoute('cart_show',[
            'id' => $id,
            'cart' => $cart
            
           ]);
     }

     #[Route('/cart/delete/{id}', name: 'cart_delete', requirements: ['id' => '\d+'])]
     public function delete($id, SessionInterface $session, ProductsRepository $productsRepository)
     {  
        $cart = $session->get('cart', []);
        // $id = $products->getID();
        
        if(!empty($cart[$id])){
            unset($cart[$id]);
        }       
            
      // on sauvegarde dans la session
      $session->set('cart', $cart);
      

    //    /** @var FlashBag */
    //    $flashBag = $session->getBag('flashes');

    //    $flashBag->add('success', "Le produit a bien été ajouté au panier");
      

         return $this->redirectToRoute('cart_show',[
            'id' => $id,
            'cart' => $cart
            
           ]);
     }


    
}
