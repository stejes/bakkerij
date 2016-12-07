<?php

namespace Steven\Eindtest\Data;

use Steven\Eindtest\Entities\User;
use Steven\Eindtest\Exceptions\CustomerExistsException;
use Steven\Eindtest\Exceptions\LoginFailedException;
use Steven\Eindtest\Exceptions\NonExistingCityException;
use PDO;

class UserDAO {

    public function create($email, $name, $firstname, $address, $cityId, $password) {
        $existingCustomer = $this->getByEmail($email);
        $cityDao = new CityDAO();
        $city = $cityDao->getById($cityId);
        if (!is_null($existingCustomer)) {
            throw new CustomerExistsException();
        } else if(is_null($city)){
            throw new NonExistingCityException();
        } else {
            $sql = "insert into customers (name, firstname, address, cityId, email, password, isBlocked) values (:name, :firstname, :address, :cityId, :email, :password, 0)";
            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':name' => $name, ':firstname' => $firstname, ":address" => $address, ":cityId" => $cityId, ":email" => $email, ":password" => $password));
            $userId = $dbh->lastInsertId();
            $dbh = null;
            $cityDAO = new CityDAO();
            $city = $cityDAO->getById($cityId);
            $user = User::create($userId, $name, $firstname, $address, $city, $email, $password, 0);
            $newUser = $this->getByEmail($email);
            if(is_null($newUser)){
                return null;
            }
            return $user;
        }
    }

    public function getByEmail($email) {
        $sql = "select id, name, firstname, address, cityId, email, password, isBlocked from customers where email = :email";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':email' => $email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        if (!$row) {

            return null;
        }
        $cityDAO = new CityDAO();
        $city = $cityDAO->getById($row["cityId"]);
        $user = User::create($row["id"], $row["name"], $row["firstname"], $row["address"], $city, $row["email"], $row["password"], $row["isBlocked"]);
        /* print_r($user);
          print 'id: ' . $user->getId(); */
        return $user;
    }

    public function isValidUser($email, $password) {
        $sql = "select email from customers where email = :email and password = :password";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':email' => $email, ':password' => $password));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        if ($row) {
            return true;
        }else{
            throw new LoginFailedException();
        }
        
    }
    
    public function update($user){
        $sql = "update customers set name = :name, firstname = :firstname, address  = :address, cityId  =:cityId where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(":name" => $user->getName(), ":firstname" => $user->getFirstname(), ":address" => $user->getAddress(),":cityId" => $user->getCity()->getId(), ":id" => $user->getId()));
        $dbh = null;
    }
    
    public function updatePass($user){
        $sql = "update customers set password = :password where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(":password" => $user->getPassword(), ":id" => $user->getId()));
        $dbh = null;
    }

}
