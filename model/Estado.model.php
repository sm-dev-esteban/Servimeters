<?php

class Estado{

    private $sql;
    private $result;
    private $connection;
    private $db;

    private $id;
    private $nombre;

    function __construct(){
        require_once "../config/DB.config.php";
        $this->db = new DB();
        $this->connection = $this->db->Conectar();
    }

    public function insert($object){
        if (isset($object["nombre"])) {
            $this->nombre = $object["nombre"];
            $this->sql = "INSERT INTO dbo.estado (nombre) VALUES (:nombre)";
            
            $this->connection->beginTransaction();
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':nombre' , $this->nombre);
            $this->result->execute();
            $this->connection->commit();
            
            echo $this->connection->lastInsertId();
        }
        
        return false;
    }

    public function delete(){}

    public function update(){}

    public function get(){}

    public function getNombre(){
        return $this->nombre;
    }

    public function setNombre($value){
        $this->nombre = $value;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($value){
        $this->id = $value;
    }
}