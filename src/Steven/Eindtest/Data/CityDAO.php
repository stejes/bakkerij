<?php



namespace Steven\Eindtest\Data;

use Steven\Eindtest\Entities\City;
use PDO;


class CityDAO {
    public function getAll(){
        $sql = "select id, zipcode, name from cities order by zipcode asc";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);

        $list = array();
        foreach ($resultSet as $row) {
            $city = City::create($row["id"], $row["zipcode"], $row["name"]);
            array_push($list, $city);
        }
        $dbh = null;
        return $list;
    }
    
    public function getById($id){
        $sql = "select id, zipcode, name from cities where id = :id";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':id' => $id));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $city = City::create($rij["id"], $rij["zipcode"], $rij["name"]);
        $dbh = null;
        return $city;
    }
}
