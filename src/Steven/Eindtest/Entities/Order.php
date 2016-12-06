<?php



namespace Steven\Eindtest\Entities;


class Order {
    private $id;
    private $orderlines = array();
    private $date;
    private $customerId;
    
    public function __construct($id, $date, $customerId) {
        $this->date = $date;
        $this->id = $id;
        $this->customerId = $customerId;
    }
    
    public function addLine($product, $amount) {
        $id = $product->getId();
        $isFound = false;
        foreach ($this->orderlines as $orderline) {
            if ($orderline->getProduct()->getId() == $id) {
                $orderline->add($amount);
                $isFound = true;
            }
        }
        if (!$isFound) {
            $orderline = new Orderline($product, $amount);

            array_push($this->orderlines, $orderline);
        }
    }

    public function getOrderlines() {
        return $this->orderlines;
    }
    
    function getId() {
        return $this->id;
    }

    function getDate() {
        return $this->date;
    }

    function getCustomerId() {
        return $this->customerId;
    }


}
