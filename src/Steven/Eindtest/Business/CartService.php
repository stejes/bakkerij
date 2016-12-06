<?php

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Entities\Cartline;
use Steven\Eindtest\Entities\Product;

class CartService {

    private $cart;

    /* public function create(){
      $cart = new Cart();
      return $this->cart;
      } */

    public function __construct() {
        $this->cart = array();
    }

    public function addLine($product, $amount) {
        $id = $product->getId();
        $isFound = false;
        foreach ($this->cart as $cartline) {
            if ($cartline->getProduct()->getId() == $id) {
                $cartline->add($amount);
                $isFound = true;
            }
        }
        if (!$isFound) {
            $cartLine = new Cartline($product, $amount);

            array_push($this->cart, $cartLine);
        }
    }

    public function getCart() {
        return $this->cart;
    }

}
