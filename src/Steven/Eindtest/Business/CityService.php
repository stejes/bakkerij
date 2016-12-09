<?php

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Data\CityDAO;

class CityService {

    public function getAll() {
        $cityDao = new CityDAO();
        return $cityDao->getAll();
    }
    
    public function getById($id){
        $cityDao = new CityDAO();
        return $cityDao->getById($id);
    }

}
