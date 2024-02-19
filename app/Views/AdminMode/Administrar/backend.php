<?php

use Controller\CentroCosto;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$action = $_GET["action"] ?? false;

$response = [
    "status" => "error",
    "message" => "failed",
];


$id = $_GET["id"] ?? 0;
$data = $_POST;


try {
    switch ($action) {
        case 'U_Clase':
        case 'U_Ceco':
            $main = [
                "U_Clase" => ["class" => null, "method" => "editClass"],
                "U_Ceco" => ["class" => null, "method" => "editCeco"]
            ][$action] ?? null;

            $class = new CentroCosto;
            $method = $main['method'];
            $result = $class->$method($data, $id);

            $response["status"] = $result["rowCount"] ? "success" : "error";
            $response["message"] = $response["status"] === "success" ? "Registro actualizado exitosamente" : "Se ha producido un error :/";
            break;

        default:
            throw new Exception("action is undefined");
    }
} catch (Exception | Error $th) {
    $response["status"] = $th instanceof Exception ? "Exception" : "Error";
    $response["message"] = $th->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);