<?php

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Entities\Cartline;
use Steven\Eindtest\Data\ProductDAO;
use Steven\Eindtest\Exceptions\AmountOutOfBoundsException;

class CartService {

    private $cart;

    /* public function create(){
      $cart = new Cart();
      return $this->cart;
      } */

    public function __construct() {
        $this->cart = array();
    }

    public function addLine($productId, $amount) {
        if($amount < 1 || !is_numeric($amount)){
            throw new AmountOutOfBoundsException();
        }
        $productDao = new ProductDAO();
        $product = $productDao->getById($productId);
        $isFound = false;
        foreach ($this->cart as $cartline) {
            if ($cartline->getProduct()->getId() == $productId) {
                $cartline->add($amount);
                $isFound = true;
            }
        }
        if (!$isFound) {
            $cartLine = new Cartline($product, $amount);

            array_push($this->cart, $cartLine);
        }
    }
    
    public function deleteLine($id){
       /* unset($this->cart[$id]);
        array_values($this->cart);*/
        array_splice($this->cart, $id, 1);
    }

    public function getCart() {
        return $this->cart;
    }

}
