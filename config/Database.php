<?php
//require_once ("config.php");
class Database {
    private $hostname = "mysql";
    private $username = "web";
    private $password = "web";
    private $dbname = "webtech2";
    public function getConnection(): PDO{
        try {
            $db = new PDO("mysql:host=$this->hostname;dbname=$this->dbname", $this->username, $this->password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $db;
    }

}