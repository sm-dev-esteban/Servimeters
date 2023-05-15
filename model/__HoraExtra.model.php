<?php

class HoraExtra{

    private $sql;
    private $result;
    private $connection;
    private $db;
    private $config;

    private $id;
    private $id_reporteHE;
    private $fecha;
    private $novedad;
    private $descuento;
    private $total;

    function __construct(){
        require_once "../config/DB.config.php";
        require_once "../config/LoadConfig.config.php";
        $this->db = new DB();
        $this->connection = $this->db->Conectar();
        $this->config = LoadConfig::getConfig();
    }

    public function insert($data){
        if (!isset($data["id_reporteHE"])) {
            return false;
        }

        try{
            $ids = array();
            $testID = '';

            $this->id_reporteHE = $data["id_reporteHE"];

            $horasExtra = json_decode($data["HE"]);

            $this->sql = "INSERT INTO dbo.HorasExtra (id_reporteHE, fecha, novedad, descuento, total) OUTPUT INSERTED.id VALUES (:id_reporteHE, :fecha, :novedad, :descuento, :total)";
//            $this->connection->beginTransaction();
            $this->result = $this->connection->prepare($this->sql);

            $this->result->bindParam(':id_reporteHE' , $this->id_reporteHE);

            foreach($horasExtra as $horaExtra){
                $this->result->bindParam(':fecha' , $horaExtra->fecha);
                $this->result->bindParam(':novedad' , $horaExtra->novedad);
                $this->result->bindParam(':descuento' , $horaExtra->descuento);
                $this->result->bindParam(':total' , $horaExtra->total);
                $this->result->execute();
                $ids[] = $this->result->fetch(PDO::FETCH_ASSOC);
//                $this->connection->commit();

                //$this->connection->lastInsertId();
            }

            echo json_encode($ids);

        } catch (\Throwable $th) {
            return false;
            throw $th;
        }
    }

    public function insertHoras($data){

        try {

            $horasExtra = json_decode($data["valuesHE"]);

            $this->sql = "INSERT INTO dbo.DetallesHoraExtra (id_horaExtra, tipo_horaExtra, cantidad) VALUES (:id_horaExtra, :tipo_horaExtra, :cantidad)";
            $this->result = $this->connection->prepare($this->sql);

            foreach($horasExtra as $horaExtra){
                foreach ($horaExtra as $item){
                    $this->result->bindParam(':id_horaExtra' , $item->id);
                    $this->result->bindParam(':tipo_horaExtra' , $item->codigo);
                    $this->result->bindParam(':cantidad' , $item->value);
                    $this->result->execute();
                }
            }

            return true;

        } catch (\Throwable $th) {
            return false;
            throw $th;
        }

    }

    public function delete($object){
        if (!isset($object["id"])) {
            return false;
        }

        try {
            $this->id = $object["id"];

            $this->sql = 'DELETE FROM HorasExtra WHERE id = :id';
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':id' , $this->id);
            return $this->result->execute();

        }catch (\Throwable $th) {
            return false;
            throw $th;
        }
    }

    public function update($object){
        
        if (!isset($object["id"])) {
            
            return false;
        }

        try {
            $this->id = $object["id"];
            $this->novedad = $object["novedad"];
            $this->fecha = $object["fecha"];
            $this->descuento = $object["descuento"];

            $this->sql = "UPDATE dbo.HorasExtra SET fecha = :fecha, novedad = :novedad, descuento = :descuento WHERE id = :id";
            
            $this->result = $this->connection->prepare($this->sql);

            $this->result->bindParam(':id' , $this->id);
            $this->result->bindParam(':fecha' , $this->fecha);
            $this->result->bindParam(':novedad' , $this->novedad);
            $this->result->bindParam(':descuento' , $this->descuento);

            $this->result->execute();
            
            return true;
        } catch (\Throwable $th) {
            return false;
            throw $th;
        }
    }

    public function updateHoras($data){

        if (!isset($data["horaExtra"])) {
            
            return false;
        }

        try {
            $this->id = $data['horaExtra'];
            $horasExtra = json_decode($data["valuesHE"]);
            
            $this->sql = "UPDATE dbo.DetallesHoraExtra SET cantidad = :cantidad WHERE id_horaExtra = :id_horaExtra AND tipo_horaExtra = :tipo_horaExtra";
            $this->result = $this->connection->prepare($this->sql);
            
            $this->result->bindParam(':id_horaExtra' , $this->id);
            
            foreach($horasExtra as $horaExtra){
                $this->result->bindParam(':tipo_horaExtra' , $horaExtra->codigo);
                $this->result->bindParam(':cantidad' , $horaExtra->value);
                $this->result->execute();
            }
        
            return true;

        } catch (\Throwable $th) {
            return false;
            throw $th;
        }
    }

    public function get(){
        $this->sql = 'SELECT * FROM dbo.HorasExtra';
        $this->result = $this->connection->prepare($this->sql);
        $this->result->execute();

        return $this->result->fetchAll(PDO::FETCH_OBJ);
    }

    public function getListado($object){
        if ($object["empleado"]) {
            $this->empleado = trim($object["empleado"]);

            $this->sql = 'SELECT R.*, A.nombre AS aprobadorNombre, A.tipo AS aprobadorTipo, A.correo AS correoAprobador, E.nombre AS estadoNombre, C.titulo AS cecoName, L.titulo AS claseName FROM ReportesHE R LEFT JOIN Aprobadores A ON R.id_aprobador = A.id INNER JOIN Estados E ON R.id_estado = E.id LEFT JOIN CentrosCosto C ON R.id_ceco = C.id LEFT JOIN Clase L ON C.id_clase = L.id WHERE R.empleado LIKE :empleado';
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':empleado' , $this->empleado);
            $this->result->execute();
    
            $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
            return $json;
        }

        return false;
    }

    public function getDetalleHora($object){
        if ($object["id"]) {
            $this->id_reporteHE = trim($object["id"]);

            $this->sql = 'SELECT D.tipo_horaExtra, T.nombre, SUM(D.cantidad) AS cantidad FROM DetallesHoraExtra D INNER JOIN TiposHE T ON D.tipo_horaExtra = T.codigo INNER JOIN HorasExtra H ON D.id_horaExtra = H.id WHERE H.id_reporteHE = :id GROUP BY D.tipo_horaExtra, T.nombre';
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':id' , $this->id_reporteHE);
            $this->result->execute();
    
            $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
            return $json;
        }

        return false;
    }

    public function getDescuentoTotal($object){
        if ($object["id"]) {
            $this->id_reporteHE = trim($object["id"]);

            $this->sql = 'SELECT SUM(H.descuento) AS cantidad FROM HorasExtra H WHERE H.id_reporteHE = :id';
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':id' , $this->id_reporteHE);
            $this->result->execute();

            $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
            return $json;
        }

        return false;
    }

    public function getListHEGestionAprobador($object){
        if (!$object["aprobador"]) {
            return false;
        }

        $this->aprobador = trim($object["aprobador"]);
        //'SELECT H.*, A.nombre AS aprobadorNombre, A.tipo AS aprobadorTipo, A.correo AS correoJefe, E.nombre AS estadoNombre, C.titulo As cecoName FROM dbo.HoraExtra H INNER JOIN dbo.Aprobador A ON H.aprobador = A.id INNER JOIN dbo.estado E ON H.estado = E.id INNER JOIN dbo.Centro_Costos C ON H.ceco = C.id WHERE H.aprobador = :aprobador AND H.estado IN (1002, 1003)'

        $this->sql = 'SELECT R.*, A.nombre AS aprobadorNombre, A.tipo AS aprobadorTipo, A.correo AS correoJefe, E.nombre AS estadoNombre, C.titulo AS cecoName, L.titulo AS claseName FROM ReportesHE R INNER JOIN Aprobadores A ON R.id_aprobador = A.id INNER JOIN Estados E ON R.id_estado = E.id LEFT JOIN CentrosCosto C ON R.id_ceco = C.id LEFT JOIN Clase L ON C.id_clase = L.id WHERE R.id_aprobador = :aprobador AND R.id_estado IN ('.$this->config->APROBACION_JEFE.','.$this->config->APROBACION_GERENTE.','.$this->config->RECHAZO_GERENTE.','.$this->config->RECHAZO_RH.','.$this->config->RECHAZO_CONTABLE.')';
        $this->result = $this->connection->prepare($this->sql);
        $this->result->bindParam(':aprobador' , $this->aprobador);
        $this->result->execute();

        $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
        return $json;
    }

    public function getListHEGestionRH(){

        $this->sql = 'SELECT R.*, A.nombre AS aprobadorNombre, A.tipo AS aprobadorTipo, A.correo AS correoJefe, E.nombre AS estadoNombre, C.titulo AS cecoName, L.titulo AS claseName FROM ReportesHE R INNER JOIN Aprobadores A ON R.id_aprobador = A.id INNER JOIN Estados E ON R.id_estado = E.id LEFT JOIN CentrosCosto C ON R.id_ceco = C.id LEFT JOIN Clase L ON C.id_clase = L.id WHERE R.id_estado IN ('.$this->config->APROBACION_RH.')';
        $this->result = $this->connection->prepare($this->sql);
        $this->result->execute();

        $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
        return $json;
    }

    public function getListHEGestionContable($object){

        $this->sql = 'SELECT R.*, A.nombre AS aprobadorNombre, A.tipo AS aprobadorTipo, A.correo AS correoJefe, E.nombre AS estadoNombre, C.titulo AS cecoName, L.titulo AS claseName FROM ReportesHE R INNER JOIN Aprobadores A ON R.id_aprobador = A.id INNER JOIN Estados E ON R.id_estado = E.id LEFT JOIN CentrosCosto C ON R.id_ceco = C.id LEFT JOIN Clase L ON C.id_clase = L.id WHERE R.id_estado IN ('.$this->config->APROBACION_CONTABLE.')';
        $this->result = $this->connection->prepare($this->sql);
        $this->result->execute();

        $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
        return $json;
    }

    public function getHorasExtraByReport($object){
        if (!$object["id_reporteHE"]) {
            return false;
        }

        $this->id_reporteHE = $object["id_reporteHE"];

        $this->sql = 'SELECT H.id, H.fecha, H.novedad, H.descuento FROM HorasExtra H WHERE H.id_reporteHE = :id_reporteHE ORDER BY H.id ASC';
        $this->result = $this->connection->prepare($this->sql);
        $this->result->bindParam(':id_reporteHE' , $this->id_reporteHE);
        $this->result->execute();

        $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
        return $json;
    }

    public function getCantHorasExtraByReport($object){
        if (!$object["id_reporteHE"]) {
            return false;
        }

        $this->id_reporteHE = $object["id_reporteHE"];

        $this->sql = 'SELECT D.id_horaExtra, D.cantidad, D.tipo_horaExtra, T.nombre FROM DetallesHoraExtra D INNER JOIN HorasExtra H ON D.id_horaExtra = H.id INNER JOIN TiposHE T ON D.tipo_horaExtra = T.codigo WHERE H.id_reporteHE = :id_reporteHE ORDER BY D.id_horaExtra ASC';
        $this->result = $this->connection->prepare($this->sql);
        $this->result->bindParam(':id_reporteHE' , $this->id_reporteHE);
        $this->result->execute();

        $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
        return $json;
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