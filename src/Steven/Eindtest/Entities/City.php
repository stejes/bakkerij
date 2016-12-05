<?php

namespace Steven\Eindtest\Entities;

class City {
    private static $idMap = array();
    private $id;
    private $zipcode;
    private $name;

    private function __construct($id, $zipcode, $name) {
        $this->id = $id;
        $this->zipcode = $zipcode;
        $this->name = $name;
    }
    
    public static function create($id, $zipcode, $name) {
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new City($id, $zipcode, $name);
        }
        return self::$idMap[$id];
    }
    
    function getId() {
        return $this->id;
    }

    function getZipcode() {
        return $this->zipcode;
    }

    function getName() {
        return $this->name;
    }



}
