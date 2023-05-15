<?php

namespace App\Cart;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $session;
    protected $requestStack;
  


    public function __construct(SessionInterface $session, RequestStack $requestStack)
    {
        $this->session = $session;
        $this->requestStack->$requestStack;
        
    }


    public function add(int $id){
        
        $cart = $this->session->get('cart', []);
        // $id = $products->getID();
        
        if(!empty($cart[$id])){
            $cart[$id]++;
        }
        else{
            $cart[$id] = 1;
        }

        // on sauvegarde dans la session
        $this->session->set('cart', $cart);
    }
}