<?php

namespace Steven\Eindtest\Entities;


class Product {
    private static $idMap = array();
    private $id;
    private $name;
    private $price;
    
    private function __construct($id, $name, $price) {
        $this->id = $id;
        $this->price = $price;
        $this->name = $name;
    }
    
    public static function create($id, $name, $price) {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Product($id, $name, $price);
        }
        return self::$idMap[$id];
    }
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getPrice() {
        return $this->price;
    }


}
