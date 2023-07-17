<?php
include_once(dirname(__DIR__) . "/config/LoadConfig.config.php");

class DB extends LoadConfig
{

    private $conexion;
    private $config;

    function __construct()
    {
        $this->config = LoadConfig::getConfig();
    }

    function Conectar()
    {
        try {

            $DNS = "sqlsrv:Server=" . ($this->config->SERVER_DB ?? false) . ";Database=" . ($this->config->DATABASE ?? false);
            $this->conexion = new PDO($DNS, $this->config->USER_DB ?? false, $this->config->PASS_DB ?? false);

            return $this->conexion;
        } catch (PDOException $th) {
            // throw $th;
            return false;
        }
    }
}
