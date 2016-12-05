<?php

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Data\CityDAO;

class CityService {

    //put your code here
    public function getAll() {
        $cityDao = new CityDAO();
        return $cityDao->getAll();
    }

}
