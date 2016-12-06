<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Steven\Eindtest\Entities;

/**
 * Description of Cartline
 *
 * @author steven.jespers
 */
class Cartline {
    private $product;
    private $amount;
    
    public function __construct($product, $amount) {
        $this->product = $product;
        $this->amount = $amount;
    }
    
    public function add($amount){
        $this->amount += $amount;
    }
    
    function getProduct() {
        return $this->product;
    }

    function getAmount() {
        return $this->amount;
    }


}
