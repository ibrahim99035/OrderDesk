<?php
namespace App\core ;
use PDO;
use PDOException;


class Database{
    private $host;
    private $username;
    private $password;
    private $db_name;
    public $conn ;
    private static $instance = null ;

    private function __construct(){
      
        try {
            $this->host = $_ENV["DB_HOST"];
            $this->username = $_ENV["DB_USER"];
            $this->password = $_ENV["DB_PASS"];
            $this->db_name = $_ENV["DB_NAME"];
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name",$this->username,$this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function getConnection(){
      
        if(self::$instance === null){
            self::$instance = new Database() ;
        }
        return self::$instance->conn ;
    }
}