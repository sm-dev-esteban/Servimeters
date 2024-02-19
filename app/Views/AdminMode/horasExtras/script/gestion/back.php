<?php

use Controller\GestionarHorasExtras;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$response = [];

try {
    $action = $_GET["action"] ?? null;

    $gestion = new GestionarHorasExtras;

    switch ($action) {
        case 'sspGestion':
            $response = $gestion::sspManageOvertime([
                ["db" => "id", "formatter" => fn ($d) => <<<HTML
                <div class="btn-group">
                    <button class="btn btn-sm" data-id="{$d}" data-toggle="modal" data-target="#modal-timeline">
                        <i class="fas fa-history text-warning"></i>
                    </button>
                    <button class="btn btn-sm" data-id="{$d}" data-toggle="modal" data-target="#modal-report">
                        <i class="fas fa-eye text-info"></i>
                    </button>
                </div>
                HTML],
                ["db" => "CC"],
                ["db" => "mesReportado"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"],
                ["db" => "id"]
            ]);
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
