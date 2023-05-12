<?php

class Recargo{

    private $sql;
    private $result;
    private $connection;
    private $db;

    private $idHE;
    private $tipoRecargo;
    private $cantidad;
    private $id_reporteHE;

    function __construct(){
        require_once "../config/DB.config.php";
        $this->db = new DB();
        $this->connection = $this->db->Conectar();
    }

    public function insert($data){

        try {
            $recargos = json_decode($data["valuesRecargo"]);

            $this->sql = "INSERT INTO dbo.Recargos (id_horaExtra, tipo_recargo, cantidad) VALUES (:id_horaExtra, :tipo_recargo, :cantidad)";
            $this->result = $this->connection->prepare($this->sql);

            foreach($recargos as $recargo){
                foreach ($recargo as $item){
                    $this->result->bindParam(':id_horaExtra' , $item->id);
                    $this->result->bindParam(':tipo_recargo' , $item->codigo);
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

    public function delete(){}

    public function update($data){

        if (!isset($data["horaExtra"])) {   
            return false;
        }

        try {
            $this->id = $data['horaExtra'];
            $recargos = json_decode($data["valuesRecargo"]);

            $this->sql = "UPDATE dbo.Recargos SET cantidad = :cantidad WHERE id_horaExtra = :id_horaExtra AND tipo_recargo = :tipo_recargo";
            $this->result = $this->connection->prepare($this->sql);

            $this->result->bindParam(':id_horaExtra' , $this->id);

            foreach($recargos as $recargo){
                $this->result->bindParam(':tipo_recargo' , $recargo->codigo);
                $this->result->bindParam(':cantidad' , $recargo->value);
                $this->result->execute();
            }

            return true;
            
        } catch (\Throwable $th) {
            return false;
            throw $th;
        }
    }

    public function get(){
        $this->sql = 'SELECT * FROM dbo.Recargo';
        $this->result = $this->connection->prepare($this->sql);
        $this->result->execute();

        return $this->result->fetchAll(PDO::FETCH_OBJ);
    }

    public function getRecargos($object){
        if ($object["id"]) {
            $this->idHE = trim($object["id"]);

            $this->sql = 'SELECT R.tipo_recargo, T.nombre, SUM(R.cantidad) AS cantidad FROM Recargos R INNER JOIN TiposRecargo T ON R.tipo_recargo = T.codigo INNER JOIN HorasExtra H ON R.id_horaExtra = H.id WHERE H.id_reporteHE = :id GROUP BY R.tipo_recargo, T.nombre';
            $this->result = $this->connection->prepare($this->sql);
            $this->result->bindParam(':id' , $this->idHE);
            $this->result->execute();
    
            $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
            return $json;
        }

        return false;
    }

    public function getCantRecargosByReport($object){
        if (!$object["id_reporteHE"]) {
            return false;
        }

        $this->id_reporteHE = $object["id_reporteHE"];

        $this->sql = 'SELECT R.id_horaExtra, R.cantidad, R.tipo_recargo, T.nombre FROM Recargos R INNER JOIN TiposRecargo T ON R.tipo_recargo = T.codigo INNER JOIN HorasExtra H ON R.id_horaExtra = H.id WHERE H.id_reporteHE = :id_reporteHE ORDER BY R.id_horaExtra ASC';
        $this->result = $this->connection->prepare($this->sql);
        $this->result->bindParam(':id_reporteHE' , $this->id_reporteHE);
        $this->result->execute();

        $json = json_encode($this->result->fetchAll(PDO::FETCH_OBJ));
        return $json;
    }

    public function gettipoRecargo(){
        return $this->tipoRecargo;
    }

    public function settipoRecargo($value){
        $this->tipoRecargo = $value;
    }

    public function getIdHE(){
        return $this->idHE;
    }

    public function setIdHE($value){
        $this->idHE = $value;
    }
}