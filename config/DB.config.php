<?php
require_once "LoadConfig.config.php";

class DB extends LoadConfig {

    private $conexion;
    private $config;

    function __construct(){
        $this->config = LoadConfig::getConfig();    
    }

    function Conectar(){

        try {

            $DNS = "sqlsrv:Server={$this->config->SERVER_DB};Database={$this->config->DATABASE}";
            $this->conexion = new PDO($DNS, $this->config->USER_DB, $this->config->PASS_DB);

            return $this->conexion;

        } catch (PDOException $th) {
            // throw $th;
            return false;
        }
        
    } 
}
