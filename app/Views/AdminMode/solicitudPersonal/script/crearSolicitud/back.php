<?php

# Includes your controller

use Config\Select2;
use Controller\SolicitudPersonal;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$action = $_GET["action"] ?? false;

$select2 = new Select2;

$solicitudPersonal = new SolicitudPersonal;

$response = [];

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
            $response = ($solicitudPersonal->read)(
                $solicitudPersonal::TABLE_SOLICITUD,
                "{$_POST["filter"]} like " . str_replace("*", "%", $_POST["search"]),
                $_POST["filter"] ?? null
            );
            break;
        case 'agregarSolicitud':
            $response = $solicitudPersonal->registrarSolicitud($data);
            break;
        default:
            # code...
            break;
    }
} catch (Exception | Error $th) {
    //throw $th;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
