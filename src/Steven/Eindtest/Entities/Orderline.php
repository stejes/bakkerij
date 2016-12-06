<?php



namespace Steven\Eindtest\Entities;


class Orderline {
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
