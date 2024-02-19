<?php

use Config\Select2;
use Controller\HorasExtras;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

try {
    $action = $_GET["action"] ?? null;

    $select2 = new Select2;
    $horasExtras = new HorasExtras;

    $response = [
        "status" => "error",
        "message" => "failed",
    ];

    $data = [...$_POST, ...$_FILES];

    switch ($action) {
        case 'sspCargo':
            $response = $select2->remoteData(
                request: $_REQUEST,
                table: "cargos",
                id: "id",
                text: "nombre"
            );
            break;

        case 'sspCeco':
            $response = $select2->remoteData(
                request: $_REQUEST,
                table: "centroscosto",
                id: "id",
                text: "nombre"
            );
            break;

        case 'sspJefes':
            $response = $select2->remoteData(
                request: $_REQUEST,
                table: "aprobador",
                id: "id",
                text: "nombre",
                config: ["condition" => "id_tipo = 2"]
            );
            break;

        case 'sspGerentes':
            $response = $select2->remoteData(
                request: $_REQUEST,
                table: "aprobador",
                id: "id",
                text: "nombre",
                config: ["condition" => "id_tipo = 3"]
            );
            break;

        case 'showTimeline':
        case 'showReport':
            $id = $data["id"] ?? 0;
            $result = $action == "showTimeline" ? $horasExtras->showTimelineReport($id) : $horasExtras->showReport($id);

            if ($result) echo str_replace(["class=\"invoice\""], ["class=\"invoice p-3 mb-3\""], $result);
            else echo "<h3>¯\_(ツ)_/¯</h3>";

            exit;
            break;

        default:
            $response["message"] = "Action is undefined";
            break;
    }
} catch (Exception | Error $th) {
    $response["message"] = $th->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
