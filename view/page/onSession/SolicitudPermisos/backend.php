<?php

use Controller\AutomaticForm;
use Model\DataTable;

session_start();


include_once "{$_SESSION["FOLDER_SIDE"]}/vendor/autoload.php";
include "{$_SESSION["FOLDER_SIDE"]}/Config.php";
include "{$_SESSION["FOLDER_SIDE"]}/conn.php";

date_default_timezone_set(TIMEZONE);

$automaticForm = new AutomaticForm;
$datatable = new DataTable;

$action = $_GET["action"] ?? false;

$table = "solicitudPermisos";
$tablePermiso = "tipoPermiso";

$data = $_POST;

switch ($action) {
    case 'I_Solicitud':
        echo json_encode($automaticForm->insert($table, $data), JSON_UNESCAPED_UNICODE);
        break;
    case 'ssp_Solicitud':
        echo json_encode($datatable->serverSide($_REQUEST, $table, [
            ["db" => "id"],
            ["db" => "funcionario"],
            ["db" => "fechaRegistro"],
            ["db" => "tipoPermiso"],
            ["db" => "fechaInicioFin"],
            ["db" => "Nhoras"],
            ["db" => "observacion"],
            ["db" => "reposicion"],
            ["db" => "fechaReposicion"],
            ["db" => "aprovador"]
        ]), JSON_UNESCAPED_UNICODE);
        break;
    case 'I_Permiso':
        echo json_encode($automaticForm->insert($tablePermiso, $data), JSON_UNESCAPED_UNICODE);
        break;
    case 'ssp_permiso':
        echo json_encode($datatable->serverSide($_REQUEST, $tablePermiso, [
            ["db" => "nombre"],
            ["db" => "detalle"],
            [
                "db" => "id", "formatter" => fn ($d) => <<<HTML
                <button type="button" data-edit="{$d}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></button>
                <button type="button" data-delete="{$d}" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                HTML
            ]
        ]), JSON_UNESCAPED_UNICODE);
        break;
    default:
        echo json_encode(["error" => "action is undefined", $_REQUEST], JSON_UNESCAPED_UNICODE);
        break;
}
