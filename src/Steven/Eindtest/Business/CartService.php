<?php

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Entities\Cartline;
use Steven\Eindtest\Data\ProductDAO;
use Steven\Eindtest\Exceptions\AmountOutOfBoundsException;

class CartService {

    /* winkelwagen array */
    private $cart;

    public function __construct() {
        $this->cart = array();
    }

    public function addLine($productId, $amount) {
        if($amount < 1 || !is_numeric($amount)){ //check of amount geldig is
            throw new AmountOutOfBoundsException();
        }
        $productDao = new ProductDAO();
        $product = $productDao->getById($productId);
        $isFound = false;
        /* check in bestaande winkelwagen of dat product al bestaat, indien ja voeg hoeveelheid toe, anders maak nieuwe aan */
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
        /* haal product eruit, slice behoudt 0-x telling in array */
        array_splice($this->cart, $id, 1);
    }

    public function getCart() {
        return $this->cart;
    }

}
