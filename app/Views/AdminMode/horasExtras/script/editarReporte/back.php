<?php

use Controller\HorasExtras;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$response = [
    "status" => "error",
    "message" => "failed",
];

try {
    $action = $_GET["action"] ?? null;

    $horasExtras = new HorasExtras;

    $data = [...$_POST, ...$_FILES];

    switch ($action) {
        case 'UPDATE':
            $id = $_GET["id"] ?? null;
            $id = base64_decode($id);
            $result = $horasExtras->actualizarReporte(
                data: $data,
                id: $id
            );

            $response["status"] = $result ? "success" : "error";
            $response["message"] = $result ? "Reporte actualizado con éxito." : "Ocurrio un error :/";
            break;
        case 'getReportesHE':
        case 'getHorasExtra':
            $id = $_GET["id"] ?? null;
            $id = base64_decode($id);
            $response = $action === "getReportesHE" ? $horasExtras->getReport(
                condition: "RHE.id = {$id}"
            ) : $horasExtras->getHours(
                id_report: $id
            );
            break;
        default:
            $response["message"] = "Action is undefined";
            break;
    }
} catch (Exception | Error $th) {
    $response["message"] = $th->getMessage();
} finally {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
