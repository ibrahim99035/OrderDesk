<?php
namespace App\Fased;
use App\DB\Conection;

class insert{
    public $table ;
    public $coulmns ;
    public $values ;
    public function __construct($table,$coulmns,$values)
    {
        $this->table = $table ;
        $this->coulmns = $coulmns ;
        $this->values = $values ;
    }

    public function insert(){
        $conn = Conection::getConnection() ;
        $sql = "INSERT INTO $this->table ($this->coulmns) VALUES ($this->values)" ;
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->rowCount() ;
        } catch (PDOException $e) {
            return false ;
        }
    }


}