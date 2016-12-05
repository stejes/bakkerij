<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Steven\Eindtest\Business;
use Steven\Eindtest\Entities\Cartline;

/**
 * Description of CartlineService
 *
 * @author steven.jespers
 */
class CartlineService {
    public function addCartline($product, $amount){
        $cartline = Cartline::create($product, $amount);
        return $cartline;
    }
    
    public function getAll(){
        $cartlineMap = Cartline::getIdMap();
        $cartlineList = array();
        foreach($cartlineMap as $cartline){
            array_push($cartlineList, $cartline);
        }
        return $cartlineList;
    }
}
