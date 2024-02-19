<?php

use Controller\HorasExtras;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$action = $_GET["action"] ?? null;

$horasExtras = new HorasExtras;

$data = $_POST ?? [];

try {
    switch ($action) {
        case 'sspReport':
            $response = $horasExtras::serverSideReport([
                ["db" => "id"],
                ["db" => "CC"],
                ["db" => "id_ceco"],
                ["db" => "id_ceco"],
                ["db" => "mesReportado"],
                ["db" => "mesReportado"],
                ["db" => "id_aprobador"],
                ["db" => "id_estado"],
                [
                    "db" => "id",
                    "formatter" => fn ($d): string => <<<HTML
                        <div class="btn-group">
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
        case 'showTimeline':
        case 'showReport':
            $id = $data["id"] ?? 0;
            $result = $action == "showTimeline" ? $horasExtras->showTimelineReport($id) : $horasExtras->showReport($id);

            if ($result) echo str_replace(["class=\"invoice\""], ["class=\"invoice p-3 mb-3\""], $result);
            else echo "<h3>¯\_(ツ)_/¯</h3>";

            exit;
            break;
        case 'value':
            $id = $data["id"] ?? 0;
            echo $horasExtras->showReport($id);

            exit;
            break;
        default:
            # code...
            break;
    }
} catch (Exception | Error $th) {
    $response["error"] = $th->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
