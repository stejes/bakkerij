<?php

namespace Steven\Eindtest\Data;


use Steven\Eindtest\Entities\Order;
use Steven\Eindtest\Entities\Orderline;
use Steven\Eindtest\Business\ProductService;
use Steven\Eindtest\Exceptions\OrderExistsException;
use Steven\Eindtest\Exceptions\NonExistingOrderException;
use Steven\Eindtest\Exceptions\UnauthorizedException;
use PDO;

class OrderDAO {

    public function add($cartlines, $userId, $date) {
        $existingOrder = $this->getByUserIdAndDate($userId, $date);
        if(!is_null($existingOrder)){
            throw new OrderExistsException();
        }
        $sql = "insert into orders (customer_id, pick_up_date) values (:customer_id, :pick_up_date)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':customer_id' => $userId, ":pick_up_date" => $date));
        $orderId = $dbh->lastInsertId();
        $dbh = null;
        //print_r($product);
        foreach ($cartlines as $cartline) {
            $productId = $cartline->getProduct()->getId();
            $amount = $cartline->getAmount();
            $sql = "insert into orderlines(order_id, product_id, amount) values(:order_id, :product_id, :amount)";
            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':order_id' => $orderId, ":product_id" => $productId, ":amount" => $amount));
            $dbh = null;
        }
    }
    
    public function getByUserIdAndDate($userId, $date){
        $sql = "select id, customer_id, pick_up_date from orders where customer_id = :customerId and pick_up_date = :date";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':customerId' => $userId, ":date" => $date));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        if (!$row){
            return null;
        }
        else{
            $order = new Order($row["id"], $row["pick_up_date"], $row["customer_id"]);
        }
        return $order;
    }

    public function getByUserId($customerId) {
        $sql = "select id, customer_id, pick_up_date from orders where customer_id = :customerId and datediff(pick_up_date,curdate()) >=0 order by pick_up_date";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':customerId' => $customerId));
        $orderList = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $order = new Order($row["id"], $row["pick_up_date"], $customerId);
            //print_r($order);
            $sql2 = "select id, order_id, product_id, amount from orderlines where order_id = :order_id";
            $dbh2 = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
            $stmt2 = $dbh2->prepare($sql2);
            //print "rowid: " . $row["id"];
            $stmt2->execute(array(':order_id' => $row["id"]));
            
            while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
                $productSvc =  new ProductService();
                $product = $productSvc->getById($row2["product_id"]);
                //print_r($product);
                
                $order->addLine($product, $row2["amount"]);
            }
            array_push($orderList, $order);
        }
        $dbh = null;

        
        return $orderList;
    }
    
    public function getById($id){
        $sql = "select id, customer_id, pick_up_date from orders where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbh = null;
        if (!$row){
            throw new NonExistingOrderException();
        }
        else{
            $order = new Order($row["id"], $row["pick_up_date"], $row["customer_id"]);
        }
        return $order;
    }
    
    public function delete($order){
        $userDao = new UserDAO();
        $user = $userDao->getByEmail($_SESSION["email"]);
        //$order = $this->getById($id);
        if($order->getCustomerId() != $user->getId()){
            throw new UnauthorizedException();
        }
        $sql = "delete from orderlines where order_id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $order->getId()));
        $dbh = null;
        $sql2 = "delete from orders where id = :id";
        $dbh2 = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt2 = $dbh2->prepare($sql2);
        $stmt2->execute(array(':id' => $order->getId()));
        $dbh2 = null;
        
    }

}
