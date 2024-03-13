<?php

use Controller\GestionarSolicitudPersonal;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

try {
    $response = [
        "message" => "error",
        "status" => "error",
    ];

    $solicitudPersonal = new GestionarSolicitudPersonal;

    $action = $_GET["action"] ?? false;

    switch ($action) {
        case 'ssp_aprobar_solicitud':
            $response = $solicitudPersonal::sspSolicitud([
                ["db" => "SP.id"],
                ["db" => "SPP.nombre", "as" => "proceso"],
                ["db" => "SP.nombre_cargo"],
                ["db" => "SP.fechaRegistro", "formatter" => fn ($d) => date("d/m/Y h:i A", strtotime($d))],
                ["db" => "SPE.nombre", "as" => "estado"],
                ["db" => "SP.id", "formatter" => fn ($d) => <<<HTML
                <span data-id="{$d}">muchos botones</span>
                HTML]
            ], ["condition" => "SPE.nombre = 'Pendiente'"]);
            break;
        case 'aprobar':
        case 'rechazar':
        case 'cancelar':
            $method = [
                "aprobar" => "aprobar",
                "rechazar" => "rechazar",
                "cancelar" => "cancelar"
            ][$action];

            if ($solicitudPersonal->$method()) {
                $response["message"] = [
                    "aprobar" => "Aprobación confirmada.",
                    "rechazar" => "Rechazo confirmado.",
                    "cancelar" => "Solicitud anulada."
                ][$method];
                $response["status"] = "success";
            } else
                $response["message"] = "Ocurrio un error";
            break;
        default:
            $response["message"] = "Action is undefined";
            break;
    }
} catch (Exception | Error $th) {
    $response["message"] = "Error: {$th->getMessage()}";
} finally {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
