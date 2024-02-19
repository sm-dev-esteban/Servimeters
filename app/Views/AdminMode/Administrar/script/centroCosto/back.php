<?php

use Config\USEFUL;
use Controller\CentroCosto;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$centroCosto = new CentroCosto;
$useful = new USEFUL;

$action = $_GET["action"] ?? false;

$response = [
    "status" => "error",
    "message" => "failed"
];

$data = $_POST;

$filter = fn(array $array, string $column, int|string $value, string $return): int|string => array_values(array_filter($array, fn($data) => $data[$column] == $value))[0][$return] ?? $value;

$clases = $centroCosto->getClass();

$optionsClases = fn($id = null): string => ($useful->options)($clases, "id", "nombre", $id);

try {
    switch ($action) {
        case 'I_Ceco':
            $result = $centroCosto->addCeco($data);

            $response["status"] = $result["lastInsertId"] ? "success" : "error";
            $response["message"] = $response["status"] === "success" ? "Se agrego la clase con exito" : "ocurrio un error al agregar la clase";

            break;
        case 'ssp_Ceco':
            $response = $centroCosto->sspCeco([
                [
                    "db" => "nombre",
                    "formatter" => fn($d, $row): string => <<<HTML
                    <span contentEditable="false" name="data[nombre]" data-original-text="{$d}" data-id="{$row['id']}">{$d}</span>
                    HTML
                ],
                [
                    "db" => "id_clase",
                    "formatter" => fn($d, $row): string => <<<HTML
                    <div data-show="{$row['id']}" class="show">{$filter($clases, "id", $d, "nombre")}</div>
                    <div data-edit="{$row['id']}" class="hide">
                        <select name="data[id_clase]" class="form-control" data-id="{$row['id']}">
                            {$optionsClases($d)}
                        </select>
                    </div>
                    HTML
                ],
                [
                    "db" => "id",
                    "formatter" => fn($d): string => <<<HTML
                    <div data-show={$d} class="show btn-group">
                        <button class="btn btn-sm text-primary" onclick="ChangeMode({$d}); enableContentEditable({$d})">
                            <i class="fa fa-pen"></i>
                        </button>
                    </div>
                    <div data-edit={$d} class="hide btn-group">
                        <button class="btn btn-sm text-success" onclick="ConfirmUpdate({$d}, 'U_Ceco')">
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