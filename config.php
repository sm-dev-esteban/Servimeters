<?php
// CONFIGURACIÓN DEL JSON CON PHP
// Nota: mejor tomar los datos con php ya que se puede acceder a la información mas facil 
define("S_NAME", $_SERVER["SERVER_NAME"]);
define("S_PORT", $_SERVER["SERVER_PORT"]);

$config = [
    "__COMMENT__" => "editar en el php la información del json",
    "LIMIT_HE" => 48,
    "SERVER_DB" => S_NAME,
    "SERVER_PORT" => S_PORT,
    "WEBSOCKET" => str_pad(S_PORT, 4, S_PORT), // Hacer la configuración manual del puerto, de momento lo voy a dejar así, pero en un servidor puede que el puerto ya esté en uso. 
    "DATABASE" => "HorasExtra",
    "USER_DB" => "sa",
    "PASS_DB" => "Es123456*",
    "HOST_EMAIL" => "smtp.office365.com",
    "USERNAME_EMAIL" => "soportesm@servimeters.net",
    "PASS_EMAIL" => "Sm-123456*",
    "PORT_EMAIL" => "587",
    "FROM_EMAIL" => "soportesm@servimeters.net",
    "URL_SITE" => getenv("REQUEST_SCHEME") . "://" . getenv("HTTP_HOST") . dirname(getenv("REQUEST_URI")) . "/",
    "FOLDER_SITE" => __DIR__ . "\\",
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

$configJSON = fopen("./config/config.json", "w");
fwrite($configJSON, json_encode($config, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
fclose($configJSON);

phpinfo();
