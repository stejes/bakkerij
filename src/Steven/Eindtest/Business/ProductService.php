<?php

namespace Steven\Eindtest\Business;
use Steven\Eindtest\Data\ProductDAO;

class ProductService {
    public function getById($id){
        $productDao = new ProductDAO();
        $product = $productDao->getById($id);
        return $product;
    }
}
