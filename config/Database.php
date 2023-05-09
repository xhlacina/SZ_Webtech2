<?php
require_once ("config.php");
class Database {
    private $hostname = "localhost";
    private $username = "xhlacina";
    private $password = "HRgY2Y7hHesuNaZ";
    private $dbname = "semestralne";
    public function getConnection() {
        try {
            $db = new PDO("mysql:host=$this->hostname;dbname=$this->dbname", $this->username, $this->password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        return $db;
    }

}