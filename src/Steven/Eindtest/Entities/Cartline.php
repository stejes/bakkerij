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

    private static $idMap = array();
    private $id;
    private $product;
    private $amount;

    private function __construct($id, $product, $amount) {
        $this->product = $product;
        $this->amount = $amount;
        $this->id = $id;
    }

    public static function create($product, $amount) {
        //self::$id++;
        /* if (!isset(self::$idMap[self::$id])) {
          self::$idMap[self::$id] = new Cartline($product, $amount);
          }
          return self::$idMap[self::$id]; */
        $id = $product->getId();
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new Cartline($id, $product, $amount);
        } else {
            self::$idMap[$id]->amount += $amount;
        }
        return self::$idMap[$id];
    }

    function getProduct() {
        return $this->product;
    }

    function getAmount() {
        return $this->amount;
    }

    static function getIdMap() {
        return self::$idMap;
    }

}
