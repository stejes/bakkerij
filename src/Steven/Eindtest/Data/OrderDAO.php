<?php

namespace Steven\Eindtest\Data;
use Steven\Eindtest\Entities\Cartline;
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

}
