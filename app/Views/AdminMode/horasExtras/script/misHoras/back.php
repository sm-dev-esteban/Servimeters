<?php

use Config\Route;
use Controller\HorasExtras;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

try {
    $action = $_GET["action"] ?? null;

    $horasExtras = new HorasExtras;

    $data = $_POST ?? [];

    $href = fn (string $url, array $gets = []) => Route::href($url, $gets);
    $encode = fn (string $string) => base64_encode($string);

    $showBtnEdit = fn ($data) => in_array($data["estado"], ["EDICION"]) ? <<<HTML
    <a href="{$href('horasExtras/editarReporte', ['report' =>$encode($data['id'])])}" class="btn btn-sm">
        <i class="fas fa-pen text-primary"></i>
    </a>
    HTML : "";

    switch ($action) {
        case 'sspReport':
            $response = $horasExtras::sspReport([
                ["db" => "RHE.id"],
                ["db" => "RHE.CC"],
                ["db" => "CC.nombre", "as" => "ceco"],
                ["db" => "C.nombre", "as" => "clase"],
                ["db" => "RHE.mesReportado", "formatter" => fn ($d): string => date("F", strtotime($d))],
                ["db" => "A.nombre", "as" => "aprobador"],
                ["db" => "S.nombre", "as" => "estado"],
                [
                    "db" => "RHE.id",
                    "formatter" => fn ($d, $row): string => <<<HTML
                        <div class="btn-group">
                            {$showBtnEdit($row)}
                            <button class="btn btn-sm" data-id="{$d}" data-toggle="modal" data-target="#modal-timeline">
                                <i class="fas fa-history text-warning"></i>
                            </button>
                            <button class="btn btn-sm" data-id="{$d}" data-toggle="modal" data-target="#modal-report">
                                <i class="fas fa-eye text-info"></i>
                            </button>
                        </div>
                    HTML
                ]
            ]);
            break;
        default:
            $response["error"] = "action is undefined";
            break;
    }
} catch (Exception | Error $th) {
    $response["error"] = $th->getMessage();
    $response["file"] = $th->getFile();
    $response["line"] = $th->getLine();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
