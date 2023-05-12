<?php

class Comentario{

    private $sql;
    private $result;
    private $connection;
    private $db;

    private $id;
    private $fecha;
    private $cuerpo;
    private $idReporteHE;
    private $creadoPor;

    function __construct(){
        require_once "../config/DB.config.php";
        $this->db = new DB();
        $this->connection = $this->db->Conectar();
    }

    public function insert($object){
        if (!isset($object["idReportesHE"])) {
            return false;
        }
            $this->fecha = $object["fecha"];
            $this->cuerpo = $object["cuerpo"];
            $reportes = json_decode($object["idReportesHE"]);
            $this->creadoPor = $object["creadoPor"];
            $this->sql = "INSERT INTO dbo.Comentarios (fecha, cuerpo, id_reporte, creadoPor) VALUES (:fecha, :cuerpo, :idReporteHE, :creadoPor)";
            
            $this->connection->beginTransaction();
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':fecha' , $this->fecha);
            $this->result->bindParam(':cuerpo' , $this->cuerpo);
            $this->result->bindParam(':creadoPor' , $this->creadoPor);

        foreach($reportes as $reporte){
            $this->idReporteHE = $reporte;
            $this->result->bindParam(':idReporteHE' , $this->idReporteHE);
            $this->result->execute();
        }
            $this->connection->commit();
            echo $this->connection->lastInsertId();
    }

    public function delete(){}

    public function update(){}

    public function getComments($object){
        if ($object["id_reporteHE"]) {
            $this->idReporteHE = trim($object["id_reporteHE"]);

            $this->sql = 'SELECT * FROM dbo.Comentarios WHERE id_reporte = :id';
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':id' , $this->idReporteHE);
            $this->result->execute();
    
            $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
            return $json;
        }

        return false;
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