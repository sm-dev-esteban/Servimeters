<?php
// CONFIGURACIÓN DEL JSON CON PHP
// Nota: mejor tomar los datos con php ya que se puede acceder a la información mas facil 
define("S_PROT", $_SERVER["SERVER_PROTOCOL"]);
define("D_PROT", $_SERVER["DOCUMENT_ROOT"]);
define("S_NAME", $_SERVER["SERVER_NAME"]);
define("S_PORT", $_SERVER["SERVER_PORT"]);
define("US_COM", getenv("COMPUTERNAME"));
// define("US_USE", getenv("USERNAME"));

$config = [
    "__COMMENT__" => "editar en el php la información del json",
    "LIMIT_HE" => 48,
    "SERVER_DB" => S_NAME,
    "SERVER_PORT" => S_PORT,
    "DATABASE" => "HorasExtra",
    "USER_DB" => "sa",
    "PASS_DB" => "Es123456*",
    "HOST_EMAIL" => "smtp.office365.com",
    "USERNAME_EMAIL" => "soportesm@servimeters.net",
    "PASS_EMAIL" => "Sm-123456*",
    "PORT_EMAIL" => "587",
    "FROM_EMAIL" => "soportesm@servimeters.net",
    "URL_SITE" => strtolower(explode("/", S_PROT)[0] . "://" . S_NAME . "/") . basename(dirname(__DIR__)) . "/",
    "FOLDER_SITE" => D_PROT . "/" . basename(dirname(__DIR__)) . "/",
    "APROBADO" => 1,
    "RECHAZO" => 2,
    "APROBACION_JEFE" => 3,
    "APROBACION_GERENTE" => 5,
    "RECHAZO_GERENTE" => 6,
    "APROBACION_RH" => 7,
    "RECHAZO_RH" => 8,
    "APROBACION_CONTABLE" => 9,
    "RECHAZO_CONTABLE" => 10,
    "EDICION" => 1002
];

$configJSON = fopen("config.JSON", "w");
fwrite($configJSON, json_encode($config, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
fclose($configJSON);
