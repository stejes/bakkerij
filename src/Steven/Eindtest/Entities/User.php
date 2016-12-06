<?php


namespace Steven\Eindtest\Entities;


class User {
    private static $idMap = array();
    private $id;
    private $name;
    private $firstname;
    private $address;
    private $city;
    private $email;
    private $password;
    private $isBlocked;
    function __construct($id, $name, $firstname, $address, $city, $email, $password, $isBlocked) {
        $this->id = $id;
        $this->name = $name;
        $this->firstname = $firstname;
        $this->address = $address;
        $this->city = $city;
        $this->email = $email;
        $this->password = $password;
        $this->isBlocked = $isBlocked;
    }
    public static function create($id, $name, $firstname, $address, $city, $email, $password, $isBlocked){
        if (!isset(self::$idMap[$id])) {
            self::$idMap[$id] = new User($id, $name, $firstname, $address, $city, $email, $password, $isBlocked);
        }
        return self::$idMap[$id];
    }
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getFirstname() {
        return $this->firstname;
    }

    function getAddress() {
        return $this->address;
    }

    function getCity() {
        return $this->city;
    }

    function getEmail() {
        return $this->email;
    }

    function getIsBlocked() {
        return $this->isBlocked;
    }



}
