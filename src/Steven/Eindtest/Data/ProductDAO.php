<?php

namespace Steven\Eindtest\Data;
use Steven\Eindtest\Entities\Product;
use PDO;

class ProductDAO {

    public function getById($id) {

        $sql = "select id, name, price from products where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $product = Product::create($row["id"], $row["name"], $row["price"]);
        $dbh = null;
        //print_r($product);
        return $product;
    }

}
