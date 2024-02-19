<?php

use Controller\Clase;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$clase = new Clase;

$action = $_GET["action"] ?? null;

$response = [
    "status" => "error",
    "message" => "failed",
];

$data = $_POST;

try {
    switch ($action) {
        case 'I_Clase':
            $result = $clase->addClass($data);

            $response["status"] = $result["lastInsertId"] ? "success" : "error";
            $response["message"] = $response["status"] === "success" ? "Se agrego la clase con exito" : "ocurrio un error al agregar la clase";

            break;
        case 'ssp_Clase':
            $response = $clase->sspClass([
                [
                    "db" => "nombre",
                    "formatter" => fn($d, $row): string => <<<HTML
                    <span contentEditable="false" name="data[nombre]" data-original-text="{$d}" data-id="{$row['id']}">{$d}</span>
                    HTML
                ],
                [
                    "db" => "id",
                    "formatter" => fn($d): string => <<<HTML
                    <div data-show="{$d}" class="show btn-group">
                        <button class="btn btn-sm text-primary" onclick="ChangeMode({$d}); enableContentEditable({$d})">
                            <i class="fa fa-pen"></i>
                        </button>
                    </div>
                    <div data-edit="{$d}" class="hide btn-group">
                        <button class="btn btn-sm text-success" onclick="ConfirmUpdate({$d}, 'U_Clase')">
                            <i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-sm text-danger" onclick="ChangeMode({$d}); disableContentEditable({$d})">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    HTML
                ]
            ]);

            break;
        default:
            $response["message"] = "action is undefined";
            break;
    }
} catch (Exception | Error $th) {
    $response["status"] = $th instanceof Exception ? "Exception" : "Error";
    $response["message"] = $th->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);