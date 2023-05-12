<?php

class TemplateReport
{
    private $sql;
    private $result;
    private $connection;
    private $db;

    private $fechaInicio;
    private $fechaFin;

    function __construct(){
        require_once "../../config/DB.config.php";
        $this->db = new DB();
        $this->connection = $this->db->Conectar();
    }

    public function detalleHoras($fechaInicio, $fechaFin){

        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;

        $this->sql = "EXEC DETALLEHORAS :fechaInicio, :fechaFin";
        $this->result = $this->connection->prepare($this->sql);

        $this->result->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
        $this->result->bindParam(':fechaFin', $this->fechaFin, PDO::PARAM_STR);

        $this->result->execute();
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detalleHoras_2($fechaInicio, $fechaFin){

        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;

        $this->sql = "EXEC DETALLEHORAS_2 :fechaInicio, :fechaFin";
        $this->result = $this->connection->prepare($this->sql);

        $this->result->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
        $this->result->bindParam(':fechaFin', $this->fechaFin, PDO::PARAM_STR);

        $this->result->execute();
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detalleReporte($fechaInicio, $fechaFin){

        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;

        $this->sql = "EXEC DETALLERECARGOS :fechaInicio, :fechaFin";
        $this->result = $this->connection->prepare($this->sql);

        $this->result->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
        $this->result->bindParam(':fechaFin', $this->fechaFin, PDO::PARAM_STR);

        $this->result->execute();
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detalleReporte_2($fechaInicio, $fechaFin){

        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;

        $this->sql = "EXEC DETALLERECARGOS_2 :fechaInicio, :fechaFin";
        $this->result = $this->connection->prepare($this->sql);

        $this->result->bindParam(':fechaInicio', $this->fechaInicio, PDO::PARAM_STR);
        $this->result->bindParam(':fechaFin', $this->fechaFin, PDO::PARAM_STR);

        $this->result->execute();
        return $this->result->fetchAll(PDO::FETCH_ASSOC);
    }

}