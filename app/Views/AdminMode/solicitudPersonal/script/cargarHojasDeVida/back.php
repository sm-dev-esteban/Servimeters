<?php

use Controller\CargarHojasDeVida;
use System\Config\AppConfig;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

try {
    $response = [
        "message" => "error",
        "status" => "error"
    ];

    $cargarHojasDeVida = new CargarHojasDeVida;

    $action = $_GET["action"] ?? null;

    switch ($action) {
        case 'upload':

            if ($cargarHojasDeVida->upload(null)) {
                $response["status"] = "success";
                $response["message"] = "Archivos recibidos.";
            } else $response["message"] = "Error al subir los archivos.";
            break;
        case 'preview':
            $report = $_GET["report"] ?? null;
            $index = $_GET["index"] ?? 0;

            $response["status"] = "success";
            $response["message"] = "mondongo";
            $response["innerHtml"] = $cargarHojasDeVida->showFilesInAccordion($report, "accordion", $index);
            break;
        case 'deleteHV':
            $report = $_GET["report"] ?? null;
            $index = $_GET["index"] ?? 0;
            $delete = $_POST["delete"] ?? null;

            $cargarHojasDeVida->dropHV($delete, $report);

            $response["status"] = "success";
            $response["message"] = "Eliminado";
            $response["innerHtml"] = $cargarHojasDeVida->showFilesInAccordion($report, "accordion", $index);
            break;
        default:
            $response["message"] = "Action is undefined";
            break;
    }
} catch (Exception | Error $e) {
    $response["status"] = "error";
    $response["message"] = "Error: {$e->getMessage()}";
} finally {
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
