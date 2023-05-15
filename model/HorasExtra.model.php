<?php
class HorasExtra
{
    private $db;
    private $connection;
    private $config;
    private $sql;
    private $result;
    function __construct()
    {
        require_once "../config/DB.config.php";
        require_once "../config/LoadConfig.config.php";
        $this->db = new DB();
        $this->connection = $this->db->Conectar();
        $this->config = LoadConfig::getConfig();
    }

    public function get(Int $id)
    {
        $this->sql = "SELECT * FROM dbo.HorasExtra where id = '{$id}'";
        // $this->sql = "SELECT * FROM dbo.HorasExtra";
        $this->result = $this->connection->prepare($this->sql);
        $this->result->execute();

        return $this->result->fetchAll(PDO::FETCH_OBJ);
    }
}
