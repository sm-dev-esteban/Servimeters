<?php

class TipoHE{

    private $sql;
    private $result;
    private $connection;
    private $db;

    private $id;
    private $titulo;

    function __construct(){
        require_once "../config/DB.config.php";
        $this->db = new DB();
        $this->connection = $this->db->Conectar();
    }

    public function insert($object){
        if (isset($object["titulo"])) {
            $this->titulo = $object["titulo"];
            $this->sql = "INSERT INTO dbo.TiposHE (titulo) VALUES (:titulo)";
            
            $this->connection->beginTransaction();
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':titulo' , $this->titulo);
            $this->result->execute();
            $this->connection->commit();
            
            echo $this->connection->lastInsertId();
        }
        
        return false;
    }

    public function delete(){}

    public function update(){}

    public function get(){
        $this->sql = 'SELECT * FROM dbo.TiposHE';
        $this->result = $this->connection->prepare($this->sql);
        $this->result->execute();

        return $this->result->fetchAll(PDO::FETCH_OBJ);
    }

    public function getTitulo(){
        return $this->titulo;
    }

    public function setTitulo($value){
        $this->titulo = $value;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($value){
        $this->id = $value;
    }
}