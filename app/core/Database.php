<?php
namespace App\core ;
use PDO;
class Database{
    private $host = "sql.freedb.tech";
    private $username = "freedb_OrderDeskITI" ;
    private $password = "c**?x3!GNV&tb37" ;
    private $db_name = "freedb_OrderDesk" ;
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