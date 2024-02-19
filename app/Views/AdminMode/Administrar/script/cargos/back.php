<?php
use Controller\Cargos;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$cargo = new Cargos;

$action = $_GET["action"] ?? null;

$response = [
    "status" => "error",
    "message" => "failed"
];

$data = $_POST;

try {
    switch ($action) {
        case 'I_Cargos':
            $result = $cargo->addCargo($data);

            $response["status"] = $result["lastInsertId"] ? "success" : "error";
            $response["message"] = $response["status"] === "success" ? "Se agrego la clase con exito" : "ocurrio un error al agregar la clase";
            break;
        case 'ssp_Cargos':
            $response = $cargo->sspCargo([
                ["db" => "nombre"],
                ["db" => "id"],
            ]);
            break;
        default:
            $response["message"] = "action is undefined";
            break;
    }
} catch (Exception | Error $th) {
    $response["status"] = $th instanceof Exception ? "Exception" : "Error";
    $response["message"] = $th->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);