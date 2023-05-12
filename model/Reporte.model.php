<?php

class Reporte
{
    private $sql;
    private $result;
    private $connection;
    private $db;

    private $id;
    private $id_estado;
    private $id_ceco;
    private $id_clase;
    private $motivoGeneral;
    private $total;
    private $id_aprobador;
    private $empleado;
    private $correoEmpleado;
    private $cc;
    private $cargo;
    private $fechaInicio;
    private $fechaFin;


    function __construct(){
        require_once "../config/DB.config.php";
        $this->db = new DB();
        $this->connection = $this->db->Conectar();
    }

    public function insert($object){
        if (!isset($object["cc"])) {
            return false;
        }

        $this->id_estado = $object["id_estado"];
        $this->id_ceco = $object["id_ceco"];
        if (empty($this->id_ceco)){
            $this->id_ceco = NULL;
        }
        $this->total = $object["total"];
        $this->id_aprobador = $object["id_aprobador"];
        if (empty($this->id_aprobador)){
            $this->id_aprobador = NULL;
        }
        $this->empleado = $object["empleado"];
        $this->correoEmpleado = $object["correoEmpleado"];
        $this->cc = $object["cc"];
        $this->cargo = $object["cargo"];
        $this->motivoGeneral = $object["proyecto"];
        if (empty($this->motivoGeneral)){
            $this->motivoGeneral = NULL;
        }
        $this->fechaInicio = $object["fechaInicio"];
        $this->fechaFin = $object["fechaFin"];


        $this->sql = "INSERT INTO dbo.ReportesHE (id_estado, id_ceco, total, id_aprobador, empleado, correoEmpleado, cc, cargo, motivoGeneral, fechaInicio, fechaFin) VALUES (:id_estado, :id_ceco, :total, :id_aprobador, :empleado, :correoEmpleado, :cc, :cargo, :motivoGeneral, :fechaInicio, :fechaFin)";
        $this->connection->beginTransaction();
        $this->result = $this->connection->prepare($this->sql);

        $this->result->bindParam(':id_estado' , $this->id_estado);
        $this->result->bindParam(':id_ceco' , $this->id_ceco);
        $this->result->bindParam(':total' , $this->total);
        $this->result->bindParam(':id_aprobador' , $this->id_aprobador);
        $this->result->bindParam(':empleado' , $this->empleado);
        $this->result->bindParam(':correoEmpleado' , $this->correoEmpleado);
        $this->result->bindParam(':cc' , $this->cc);
        $this->result->bindParam(':cargo' , $this->cargo);
        $this->result->bindParam(':motivoGeneral' , $this->motivoGeneral);
        $this->result->bindParam(':fechaInicio' , $this->fechaInicio);
        $this->result->bindParam(':fechaFin' , $this->fechaFin);

        $this->result->execute();
        $this->connection->commit();

        echo $this->connection->lastInsertId();
    }

    public function update($object){
        if (!isset($object["id"])) {
            return false;
        }

        $this->id = $object["id"];
        $this->id_estado = $object["id_estado"];
        $this->id_ceco = $object["id_ceco"];
        if (empty($this->id_ceco)){
            $this->id_ceco = NULL;
        }
        $this->total = $object["total"];
        $this->id_aprobador = $object["id_aprobador"];
        if (empty($this->id_aprobador)){
            $this->id_aprobador = NULL;
        }
        $this->cc = $object["cc"];
        $this->cargo = $object["cargo"];
        $this->motivoGeneral = $object["proyecto"];
        if (empty($this->motivoGeneral)){
            $this->motivoGeneral = NULL;
        }
        $this->correoEmpleado = $object["correoEmpleado"];
        $this->fechaInicio = $object["fechaInicio"];
        $this->fechaFin = $object["fechaFin"];


        $this->sql = "UPDATE dbo.ReportesHE SET id_estado = :id_estado, id_ceco = :id_ceco, total = :total, id_aprobador = :id_aprobador, cc = :cc, cargo = :cargo, motivoGeneral = :motivoGeneral, fechaInicio = :fechaInicio, fechaFin = :fechaFin, correoEmpleado = :correoEmpleado WHERE id = :id";
        $this->result = $this->connection->prepare($this->sql);

        $this->result->bindParam(':id' , $this->id);
        $this->result->bindParam(':id_estado' , $this->id_estado);
        $this->result->bindParam(':id_ceco' , $this->id_ceco);
        $this->result->bindParam(':total' , $this->total);
        $this->result->bindParam(':id_aprobador' , $this->id_aprobador);
        $this->result->bindParam(':cc' , $this->cc);
        $this->result->bindParam(':cargo' , $this->cargo);
        $this->result->bindParam(':motivoGeneral' , $this->motivoGeneral);
        $this->result->bindParam(':correoEmpleado' , $this->correoEmpleado);
        $this->result->bindParam(':fechaInicio' , $this->fechaInicio);
        $this->result->bindParam(':fechaFin' , $this->fechaFin);

        $this->result->execute();

        return true;
    }

    public function updateCECO($object){
        if (!isset($object["id"])) {
            return false;
        }

        $this->id = $object["id"];
        $this->id_ceco = $object["id_ceco"];

        if (empty($this->id_ceco)){
            $this->id_ceco = NULL;
        }

        $this->sql = "UPDATE dbo.ReportesHE SET id_ceco = :id_ceco WHERE id = :id";
        $this->result = $this->connection->prepare($this->sql);

        $this->result->bindParam(':id' , $this->id);
        $this->result->bindParam(':id_ceco' , $this->id_ceco);

        $this->result->execute();

        return true;
    }

    public function updateEstado($object){

        try {
            if (!$object["reportes"]) {
                return false;
            }

            $reportes = json_decode($object["reportes"]);

            $this->id_aprobador = trim($object["aprobador"]);
            $this->id_estado = $object["estado"];

            $this->sql = 'UPDATE dbo.ReportesHE SET id_estado = :estado, id_aprobador = :aprobador WHERE id = :id';
            $this->result = $this->connection->prepare($this->sql);

            $this->result->bindParam(':aprobador' , $this->id_aprobador);
            $this->result->bindParam(':estado' , $this->id_estado);

            foreach($reportes as $reporte){
                $this->id = $reporte;
                $this->result->bindParam(':id' , $this->id);
                $this->result->execute();
            }

            return true;
        } catch (\Throwable $th) {
            return false;
            throw $th;
        }

    }

    function updateTotal($object){
        try {
            if (!$object["reporte"]) {
                return false;
            }

            $this->id = $object["reporte"];
            $this->total = trim($object["total"]);

            $this->sql = 'UPDATE dbo.ReportesHE SET total = :total WHERE id = :id';
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':id' , $this->id);
            $this->result->bindParam(':total' , $this->total);
            $this->result->execute();

            return true;
        } catch (\Throwable $th) {
            return false;
            throw $th;
        }
    }

    public function rejectEstado($object){

        try {
            if (!$object["reportes"]) {
                return false;
            }

            $reportes = json_decode($object["reportes"]);
            $this->id_estado = $object["estado"];

            $this->sql = 'UPDATE dbo.ReportesHE SET id_estado = :estado WHERE id = :id';
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':estado' , $this->id_estado);

            foreach($reportes as $reporte){
                $this->id = $reporte;
                $this->result->bindParam(':id' , $this->id);
                $this->result->execute();
            }

            return true;
        } catch (\Throwable $th) {
            return false;
            throw $th;
        }

    }

    function updateProyecto($object){
        try {
            if (!$object["reportes"]) {
                return false;
            }

            $reportes = json_decode($object["reportes"]);

            $this->motivoGeneral = trim($object["proyecto"]);

            $this->sql = 'UPDATE dbo.ReportesHE SET motivoGeneral = :motivoGeneral WHERE id = :id AND motivoGeneral IS NULL';
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':motivoGeneral' , $this->motivoGeneral);

            foreach($reportes as $reporte){
                $this->id = $reporte;
                $this->result->bindParam(':id' , $this->id);
                $this->result->execute();
            }

            return true;
        } catch (\Throwable $th) {
            return false;
            throw $th;
        }
    }

}