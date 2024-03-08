<?php

# Includes your controller

use Config\AutoComplete;
use Config\Select2;
use Controller\SolicitudPersonal;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$action = $_GET["action"] ?? false;

$select2 = new Select2;
$autoComplete = new AutoComplete;

$solicitudPersonal = new SolicitudPersonal;

$response = [
    "message" => "error",
    "status" => "error"
];

$data = $_POST ?? [];

try {
    switch ($action) {
        case 'sspProceso':
        case 'sspContrato':
        case 'sspHorario':
        case 'sspMotivoRequisicion':
            $table = [
                "sspContrato" => $solicitudPersonal::TABLE_SOLICITUD_TIPO_CONTRATO,
                "sspProceso" => $solicitudPersonal::TABLE_SOLICITUD_PROCESO,
                "sspHorario" => $solicitudPersonal::TABLE_SOLICITUD_HORARIO,
                "sspMotivoRequisicion" => $solicitudPersonal::TABLE_SOLICITUD_MOTIVO_REQUISICION,
            ][$action] ?? null;

            $response = $select2->remoteData(
                request: $_REQUEST,
                table: $table,
                id: "id",
                text: "nombre"
            );
            break;
        case 'sspAprobador':
            $response = $select2->remoteData(
                request: $_REQUEST,
                table: "aprobador",
                id: "id",
                text: "nombre",
                config: ["condition" => "apruebaSolicitudPersonal = 1"]
            );
            break;
        case 'autoComplete':
            $response = $autoComplete->sql($solicitudPersonal::TABLE_SOLICITUD);
            break;
        case 'agregarSolicitud':
            $response = $solicitudPersonal->registrarSolicitud($data);
            break;
        default:
            $response["message"] = "action is undefined";
            break;
    }
} catch (Exception | Error $th) {
    $response["message"] = "error: {$th->getMessage()}";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
