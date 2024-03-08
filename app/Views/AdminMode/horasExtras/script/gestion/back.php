<?php

use Controller\GestionarHorasExtras;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$response = [
    "status" => "error",
    "message" => "error",
];

try {
    $action = $_GET["action"] ?? null;

    $gestion = new GestionarHorasExtras;
    $session = fn ($name) => $_SESSION[$name] ?? null;

    switch ($action) {
        case 'sspGestion':
            $response = $gestion::sspManageOvertime(
                columns: [
                    ["db" => "RHE.id", "formatter" => fn ($d) => <<<HTML
                    <div class="btn-group">
                        <button class="btn btn-sm" data-id="{$d}" data-toggle="modal" data-target="#modal-timeline">
                            <i class="fas fa-history text-warning"></i>
                        </button>
                        <button class="btn btn-sm" data-id="{$d}" data-toggle="modal" data-target="#modal-report">
                            <i class="fas fa-eye text-info"></i>
                        </button>
                    </div>
                    HTML],
                    ["db" => "RHE.CC"],
                    ["db" => "RHE.mesReportado", "formatter" => fn ($d) => date("M", strtotime($d))],
                    ["db" => "RHE.reportador_por"],
                    ["db" => "S.nombre", "as" => "estado", "formatter" => fn ($d) => <<<HTML
                    <span data-estado="{$d}">{$d}</span>
                    HTML],
                    ["db" => "C.nombre", "as" => "clase"],
                    ["db" => "CC.nombre", "as" => "ceco"],
                    ["db" => "RHE.Total_Descuento"],
                    ["db" => "RHE.Total_Ext_Diu_Ord"],
                    ["db" => "RHE.Total_Ext_Noc_Ord"],
                    ["db" => "RHE.Total_Ext_Diu_Fes"],
                    ["db" => "RHE.Total_Ext_Noc_Fes"],
                    ["db" => "RHE.Total_Rec_Noc"],
                    ["db" => "RHE.Total_Rec_Fes_Diu"],
                    ["db" => "RHE.Total_Rec_Fes_Noc"],
                    ["db" => "RHE.Total_Rec_Ord_Fes_Noc"]
                ],
                config: [
                    "condition" => "RHE.id_aprobador = '{$session("id")}'"
                ]
            );
            break;
        case 'aprobar':
            if ($gestion->aprobar()) {
                $response["message"] = "Aprobado con éxito.";
                $response["status"] = "success";
            } else {
                $response["message"] = "Ocurrio un error.";
            }
            break;
        case 'rechazar':
            if ($gestion->rechazar()) {
                $response["message"] = "Rechazo Confirmado.";
                $response["status"] = "success";
            } else {
                $response["message"] = "Ocurrio un error.";
            }
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
