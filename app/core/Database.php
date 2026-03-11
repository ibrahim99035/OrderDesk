<?php
namespace App\core ;
use PDO;
class Database{
    private $host = DB_HOST;
    private $username = DB_USER ;
    private $password = DB_PASS;
    private $db_name = DB_NAME ;
    public $conn ;
    private static $instance = null ;

    private function __construct(){
      
        try {
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