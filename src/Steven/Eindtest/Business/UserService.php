<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Exceptions\EmptyFieldsException;
use Steven\Eindtest\Exceptions\PasswordsDontMatchException;
use Steven\Eindtest\Exceptions\WrongPasswordException;
use Steven\Eindtest\Data\UserDAO;
use Steven\Eindtest\Data\CityDAO;

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
            if (!is_null($user)) {
                return $passwordString;
            }
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

    public function checkLogin($email, $password) {
        $userDao = new UserDAO();
        //print "user" . $user;
        //print "passding: " . sha1($password);
        $user = $userDao->getByEmail($email);
        if (!is_null($user) && $user->getIsBlocked() == false && $user->getPassword() == sha1($password)) {
            return true;
        }
        return false;
    }

    public function getByEmail($email) {
        $userDao = new UserDAO();
        return $userDao->getByEmail($email);
    }

    public function editData($email, $firstname, $name, $address, $cityId) {
        $userDao = new UserDAO();
        $user = $userDao->getByEmail($email);
        $user->setFirstname($firstname);
        $user->setName($name);
        $user->setAddress($address);
        $cityDao = new CityDAO();
        $city = $cityDao->getById($cityId);
        $user->setCity($city);
        $userDao->update($user);
    }

    public function editPassword($email, $oldpassword, $password, $password2) {
        if ($password == $password2) {
            $userDao = new UserDao();
            $user = $userDao->getByEmail($email);
            if ($user->getPassword() == sha1($oldpassword)) {
                $user->setPassword(sha1($password));
                $userDao->update($user);
            }
            else{
                throw new WrongPasswordException();
            }
        }
        else{
            throw new PasswordsDontMatchException();
        }
    }

}
