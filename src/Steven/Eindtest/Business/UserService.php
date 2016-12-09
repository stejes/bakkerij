<?php

namespace Steven\Eindtest\Business;

use Steven\Eindtest\Exceptions\EmptyFieldsException;
use Steven\Eindtest\Exceptions\PasswordsDontMatchException;
use Steven\Eindtest\Exceptions\WrongPasswordException;
use Steven\Eindtest\Exceptions\LoginFailedException;
use Steven\Eindtest\Exceptions\NotAnEmailException;
use Steven\Eindtest\Exceptions\InvalidFieldsException;
use Steven\Eindtest\Data\UserDAO;
use Steven\Eindtest\Data\CityDAO;

class UserService {

    public function registerUser($email, $name, $firstname, $address, $city) {
        /* check op lege velden */
        if ($email == "" || $name == "" || $firstname == "" || $address == "" || $city == "") {
            throw new EmptyFieldsException();
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //check of email geldig is
            throw new NotAnEmailException();
        }
        /* check of naam en voornaam enkel letters zijn en adres letters (of'-') plus spatie plus cijfers */ else if (!preg_match("/^[a-zA-Z]*$/", $firstname) || !preg_match("/^[a-zA-Z]*$/", $name) || !preg_match("/^([a-z- ]*)\s+([0-9]+)$/i", $address)) {
            throw new InvalidFieldsException();
        } else { //genereer paswoord, maak user aan
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
        /* paswoord generatie, 6 random cijfers en/of hoofdletters */
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function checkLogin($email, $password) {
        /* check of user bestaat, paswoord juist is en user niet geblokkeerd is */
        $userDao = new UserDAO();
        $user = $userDao->getByEmail($email);
        if (!is_null($user) && $user->getIsBlocked() == false && $user->getPassword() == sha1($password)) {
            return true;
        }
        throw new LoginFailedException();
    }

    public function getByEmail($email) {
        $userDao = new UserDAO();
        return $userDao->getByEmail($email);
    }

    public function editData($email, $firstname, $name, $address, $cityId) {
        /* check op lege velden */
        if ($name == "" || $firstname == "" || $address == "" || $cityId == "") {
            throw new EmptyFieldsException();
        }
        /* check of naam en voornaam enkel letters zijn en adres letters (of'-') plus spatie plus cijfers */ else if (!preg_match("/^[a-zA-Z]*$/", $firstname) || !preg_match("/^[a-zA-Z]*$/", $name) || !preg_match("/^([a-z- ]*)\s+([0-9]+)$/i", $address)) {
            throw new InvalidFieldsException();
        }
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
        $userDao = new UserDao();
        $user = $userDao->getByEmail($email);
        if ($user->getPassword() == sha1($oldpassword)) {
            if ($password != $password2) {
                throw new PasswordsDontMatchException();
            }

            $user->setPassword(sha1($password));
            $userDao->update($user);
        } else {
            throw new WrongPasswordException();
        }
    }

}
