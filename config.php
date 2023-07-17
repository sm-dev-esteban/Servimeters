<?php
// CONFIGURACIÓN DEL JSON CON PHP
// Nota: mejor tomar los datos con php ya que se puede acceder a la información mas facil 
include __DIR__ . "/controller/automaticForm.php";
define("S_NAME", $_SERVER["SERVER_NAME"]);
define("S_PORT", $_SERVER["SERVER_PORT"]);

define("ACTIVE", "localhost");

define("MODE", [
    "localhost" => [],
    "produccion" => [
        "PASS_DB" => "S3rv1830117370*",
        "SERVER_DB" => "10.10.10.6",
        "DATABASE" => "HorasExtra_temp",
        "WEBSOCKET" => 8080
    ]
]);

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
    "APROBADO" => getEstados("aprobado"),
    "RECHAZO" => getEstados("rechazado"),
    "APROBACION_JEFE" => getEstados("revision j"),
    "APROBACION_GERENTE" => getEstados("revision g"),
    "RECHAZO_GERENTE" => getEstados("rechazo g"),
    "APROBACION_RH" => getEstados("revision r"),
    "RECHAZO_RH" => getEstados("rechazo r"),
    "APROBACION_CONTABLE" => getEstados("revision c"),
    "RECHAZO_CONTABLE" => getEstados("rechazo c"),
    "EDICION" => getEstados("edicion")
];

$configJSON = fopen("./config/config.json", "w");
fwrite($configJSON, json_encode(array_merge($config, MODE[ACTIVE] ?? []), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
fclose($configJSON);

phpinfo();
function getEstados($filter, $column = "nombre", $return = "id", $config = ["like" => true, "notResult" => "Error"])
{
    return AutomaticForm::getValueSql($filter, $column, $return, "Estados", $config);
}
