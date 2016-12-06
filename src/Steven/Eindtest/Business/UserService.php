<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Exceptions\EmptyFieldsException;
use Steven\Eindtest\Data\UserDAO;

/**
 * Description of UserService
 *
 * @author steven.jespers
 */
class UserService {

    public function registerUser($email, $name, $firstname, $address, $city) {
        if ($email == "" || $name == "" || $firstname == "" || $address == "" || $city == "") {
            throw new EmptyFieldsException();
        } else {
            $passwordString = $this->generatePassword();
            $password = sha1($passwordString);
            $userDao = new UserDAO();
            $user = $userDao->create($email, $name, $firstname, $address, $city, $password);
            return $passwordString;
        }
        return false;
    }

    public function generatePassword() {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function checkLogin($email, $password){
        $userDao = new UserDAO();
        //print "user" . $user;
        if($userDao->isValidUser($email, sha1($password))){
            return true;
        }
        return false;
    }
    
    public function getByEmail($email){
        $userDao = new UserDAO();
        return $userDao->getByEmail($email);
    }

}
