<?php

namespace Steven\Eindtest\Data;


use Steven\Eindtest\Entities\Order;
use Steven\Eindtest\Entities\Orderline;
use Steven\Eindtest\Business\ProductService;

use PDO;

class OrderDAO {

    public function add($cartlines, $userId, $date) {
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

    public function getByUserId($customerId) {
        $sql = "select id, customer_id, pick_up_date from orders where customer_id = :customerId order by pick_up_date";
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

}
