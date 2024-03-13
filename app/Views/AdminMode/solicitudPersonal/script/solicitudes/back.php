<?php

use Config\Route;
use Controller\SolicitudPersonal;
use System\Config\AppConfig;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$response = [
    "message" => "error",
    "status" => "error",
];

try {
    $appConfig = new AppConfig;
    $solicitudPersonal = new SolicitudPersonal;

    $session = fn (string $name): mixed => $_SESSION[$name] ?? null;

    $action = $_GET["action"] ?? false;

    $href = fn (string $url, array $get = []) => Route::href(
        url: $url,
        get: $get
    );

    switch ($action) {
        case 'ssp_solicitud':
            $response = $solicitudPersonal::sspSolicitud([
                ["db" => "SP.id"],
                ["db" => "SPP.nombre", "as" => "proceso"],
                ["db" => "SP.nombre_cargo"],
                ["db" => "SPE.nombre", "as" => "estado"],
                ["db" => "SP.id", "formatter" => function ($d, $row) use ($href) {
                    [
                        "estado" => $estado
                    ] = $row;

                    $estado = strtoupper($estado);

                    $encodeID = base64_encode($d);
                    $btnsExtras = [];

                    if ($estado === "APROBADO JEFE") $btnsExtras[] = <<<HTML
                    <a href="{$href('solicitudPersonal/cargarHojasDeVida', ['report' =>$encodeID])}" class="btn btn-sm" data-id="{$d}">
                        <i class="fas fa-file-upload text-primary"></i>
                    </a>
                    <a href="{$href('solicitudPersonal/verHojasDeVida', ['report' =>$encodeID])}" class="btn btn-sm" data-id="{$d}">
                        <i class="fas fa-folder text-primary"></i>
                    </a>
                    HTML;

                    $btns = implode(PHP_EOL, $btnsExtras);

                    return <<<HTML
                    <div class="btn-group">
                        {$btns}
                        <button type="button" class="btn btn-sm" data-id="{$d}">
                            <i class="fas fa-history text-warning"></i>
                        </button>
                        <button type="button" class="btn btn-sm" data-id="{$d}">
                            <i class="fas fa-eye text-info"></i>
                        </button>
                    </div>
                    HTML;
                }]
            ], ["condition" => "SP.radicado_por like '%{$session("usuario")}%'"]);
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
