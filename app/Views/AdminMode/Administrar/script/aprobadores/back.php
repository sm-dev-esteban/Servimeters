<?php

use Config\USEFUL;
use Controller\Aprobador;

include_once explode("\\app\\", __DIR__)[0] . "/vendor/autoload.php";

$aprobador = new Aprobador;
$useful = new USEFUL;

$action = $_GET["action"] ?? false;

$response = [
    "status" => "error",
    "message" => "failed"
];

$data = $_POST;

$checked = fn (int $status) => $status ? "checked" : "";

$color = fn (int $status) => $status ? "var(--success)" : "var(--danger)";
$style = fn (int $status) => str_replace("\n", "", "
    display: flex;
    justify-content: center;
    align-items: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: {$color($status)};
    color: {$color($status)};
    user-select: none;
");

$tipo = $aprobador->getApproverType();
$opcionesTipo = fn ($d = null): string => ($useful->options)($tipo, "id", "nombre", $d);

$gestion = $aprobador->getApproverManages();
$opcionesGestion = fn ($d = null): string => ($useful->options)($gestion, "id", "nombre", $d);


try {
    switch ($action) {
        case 'I_Aprobador':
            $result = $aprobador->addApprover($data);

            $response["status"] = $result["lastInsertId"] ? "success" : "error";
            $response["message"] = $response["status"] === "success" ? "Se agrego la clase con exito" : "ocurrio un error al agregar la clase";
            break;
        case 'ssp_Aprobador':
            $response = $aprobador->sspApprover([
                [
                    "db" => "APPROVER.nombre",
                    "formatter" => fn ($d, $row): string => <<<HTML
                    <span contentEditable="false" name="data[nombre]" data-original-text="{$d}" data-id="{$row['id']}">{$d}</span>
                    HTML
                ],
                [
                    "db" => "APPROVER.email",
                    "formatter" => fn ($d, $row): string => <<<HTML
                    <span contentEditable="false" name="data[email]" data-original-text="{$d}" data-id="{$row['id']}">{$d}</span>
                    HTML
                ],
                [
                    "db" => "TYPE.nombre",
                    "as" => "tipo",
                    "formatter" => fn ($d, $row): string => <<<HTML
                    <div data-show="{$row['id']}" class="show">{$d}</div>
                    <div data-edit="{$row['id']}" class="hide">
                        <select name="data[id_tipo]" class="form-control" data-id="{$row['id']}">
                            {$opcionesTipo($d)}
                        </select>
                    </div>
                    HTML
                ],
                [
                    "db" => "MANAGES.nombre",
                    "as" => "gestion",
                    "formatter" => fn ($d, $row): string => <<<HTML
                    <div data-show="{$row['id']}" class="show">{$d}</div>
                    <div data-edit="{$row['id']}" class="hide">
                        <select name="data[id_gestiona]" class="form-control" data-id="{$row['id']}">
                            {$opcionesGestion($d)}
                        </select>
                    </div>
                    HTML
                ],
                [
                    "db" => "APPROVER.admin",
                    "formatter" => fn ($d, $row, $i): string => <<<HTML
                    <div data-show="{$row['id']}" class="show">
                        <span style="{$style($d)}">{$d}</span>
                    </div>
                    <div data-edit="{$row['id']}" class="hide">
                        <div class="icheck-primary">
                            <input type="checkbox" name="data[admin]" value="1" id="check{$row['id']}{$i}" {$checked($d)}>
                            <label for="check{$row['id']}{$i}"></label>
                        </div>
                    </div>
                    HTML
                ],
                [
                    "db" => "APPROVER.apruebaSolicitudPersonal",
                    "formatter" => fn ($d, $row, $i): string => <<<HTML
                    <div data-show="{$row['id']}" class="show">
                        <span style="{$style($d)}">{$d}</span>
                    </div>
                    <div data-edit="{$row['id']}" class="hide">
                        <div class="icheck-primary">
                            <input type="checkbox" name="data[apruebaSolicitudPersonal]" value="1" id="check{$row['id']}{$i}" {$checked($d)}>
                            <label for="check{$row['id']}{$i}"></label>
                        </div>
                    </div>
                    HTML
                ],
                [
                    "db" => "APPROVER.apruebaSolicitudPermisos",
                    "formatter" => fn ($d, $row, $i): string => <<<HTML
                    <div data-show="{$row['id']}" class="show">
                        <span style="{$style($d)}">{$d}</span>
                    </div>
                    <div data-edit="{$row['id']}" class="hide">
                        <div class="icheck-primary">
                            <input type="checkbox" name="data[apruebaSolicitudPermisos]" value="1" id="check{$row['id']}{$i}" {$checked($d)}>
                            <label for="check{$row['id']}{$i}"></label>
                        </div>
                    </div>
                    HTML
                ],
                [
                    "db" => "APPROVER.id",
                    "formatter" => fn ($d): string => <<<HTML
                    <div data-show={$d} class="show btn-group">
                        <button class="btn btn-sm text-primary" onclick="ChangeMode({$d}); enableContentEditable({$d})">
                            <i class="fa fa-pen"></i>
                        </button>
                    </div>
                    <div data-edit={$d} class="hide btn-group">
                        <button class="btn btn-sm text-success" onclick="ConfirmUpdate({$d}, 'U_Aprobador')">
                            <i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-sm text-danger" onclick="ChangeMode({$d}); disableContentEditable({$d})">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    HTML
                ],
            ], ["condition" => "APPROVER.id != 1"]);
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
