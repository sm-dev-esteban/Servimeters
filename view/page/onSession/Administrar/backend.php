<?php session_start();

use Controller\AutomaticForm;
use Model\DataTable;

include "{$_SESSION["FOLDER_SIDE"]}/Config.php";
include_once "{$_SESSION["FOLDER_SIDE"]}/vendor/autoload.php";

$automatic = new AutomaticForm();

switch ($_REQUEST["action"] ?? false) {
    case 'I_Clase':
    case 'I_CECO':
    case 'I_Aprobador':
        $resp = $automatic::insert([
            'I_Clase' => "Clase",
            'I_CECO' => "CentrosCosto",
            'I_Aprobador' => "Aprobadores"
        ][$_REQUEST["action"]], $_REQUEST);
        unset($resp["error"], $resp["query"]);
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        break;
    case 'U_Clase':
    case 'U_CECO':
    case 'U_Aprobador':
        $resp = $automatic::update([
            'U_Clase' => "Clase",
            'U_CECO' => "CentrosCosto",
            'U_Aprobador' => "Aprobadores"
        ][$_REQUEST["action"]], $_REQUEST, ["id" => $_REQUEST["id"] ?? false]);
        unset($resp["error"], $resp["query"]);
        echo json_encode($resp, JSON_UNESCAPED_UNICODE);
        break;
    case 'ssp_Clase':
        $table = "Clase";
        $column = [
            [
                "db" => "titulo", "formatter" => function ($d, $row) {
                    $dE = base64_encode($row["id"]);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            {$d}
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <input type="text" name="titulo" class="form-control" value="{$d}">
                        </div>
                    HTML;
                }
            ], [
                "db" => "id", "formatter" => function ($d, $row) {
                    $dE = base64_encode($d);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            <button type="button" class="rounded btn-info m-1" onclick="ChangeMode({$d})"><i class="fas fa-pen"></i></button>
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <button type="button" class="rounded btn-success m-1" onclick="ConfirmUpdate({$d}, 'U_Clase')"><i class="fas fa-check"></i></button>
                            <button type="button" class="rounded btn-danger m-1" onclick="ChangeMode({$d})"><i class="fas fa-times"></i></button>
                        </div>
                    HTML;
                }
            ]
        ];
        echo json_encode(DataTable::serverSide($_REQUEST, $table, $column), JSON_UNESCAPED_UNICODE);
        break;
    case 'ssp_CECO':
        $table[] = "CentrosCosto CECO";
        $table[] = "inner join Clase C on C.id = CECO.id_clase";

        $column = [
            [
                "db" => "CECO.titulo", "as" => "centroCosto", "formatter" => function ($d, $row) {
                    $dE = base64_encode($row["id"]);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            {$d}
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <input type="text" name="titulo" class="form-control" value="{$d}">
                        </div>
                    HTML;
                }
            ], [
                "db" => "C.titulo", "as" => "clase", "formatter" => function ($d, $row) {
                    include FOLDER_SIDE . "/conn.php";
                    $Clases = $db->executeQuery(<<<SQL
                    SELECT * FROM Clase
                    SQL);
                    $options = "";
                    foreach ($Clases as $data) {
                        $selected = ($data['id'] == $row['id_clase']) ? "selected" : "";
                        $options .= <<<HTML
                            <option value="{$data['id']}" {$selected}>
                                {$data['titulo']}
                            </option>
                        HTML;
                    }

                    $dE = base64_encode($row["id"]);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            {$d}
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <select name="id_clase" class="form-control">
                                {$options}
                            </select>
                        </div>
                    HTML;
                }
            ], [
                "db" => "CECO.id", "formatter" => function ($d, $row) {
                    $dE = base64_encode($d);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            <button type="button" class="rounded btn-info m-1" onclick="ChangeMode({$d})"><i class="fas fa-pen"></i></button>
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <button type="button" class="rounded btn-success m-1" onclick="ConfirmUpdate({$d}, 'U_CECO')"><i class="fas fa-check"></i></button>
                            <button type="button" class="rounded btn-danger m-1" onclick="ChangeMode({$d})"><i class="fas fa-times"></i></button>
                        </div>
                    HTML;
                }
            ]
        ];
        echo json_encode(DataTable::serverSide($_REQUEST, $table, $column, [
            "columns" => "CECO.*, CECO.titulo centroCosto, C.titulo clase"
        ]), JSON_UNESCAPED_UNICODE);
        break;
    case 'ssp_Aprobador':

        $table[] = "Aprobadores A";
        $table[] = "inner join HorasExtras_Aprobador_Tipo B on A.id_tipo = B.id";
        $table[] = "inner join HorasExtras_Aprobador_Gestiona C on A.id_gestiona = C.id";
        $table[] = "inner join HorasExtras_Aprobador_Administra D on A.id_Esadmin = D.id";
        $table[] = "inner join HorasExtras_Aprobador_SolicitudPersonal E on A.id_solicitudPersonal = E.id";

        $column = [
            [
                "db" => "A.nombre", "formatter" => function ($d, $row) {
                    $dE = base64_encode($row["id"]);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            {$d}
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <input type="text" name="nombre" class="form-control" value="{$d}">
                        </div>
                    HTML;
                }
            ], [
                "db" => "A.mail", "formatter" => function ($d, $row) {
                    $dE = base64_encode($row["id"]);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            {$d}
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <input type="email" name="mail" class="form-control" value="{$d}">
                        </div>
                    HTML;
                }
            ], [
                "db" => "B.nombre", "as" => "tipo", "formatter" => function ($d, $row) {
                    include FOLDER_SIDE . "/conn.php";
                    $Tipo = $db->executeQuery(<<<SQL
                    SELECT * FROM HorasExtras_Aprobador_Tipo
                    SQL);
                    $options = "";
                    foreach ($Tipo as $data) {
                        $selected = ($data['id'] == $row['id_tipo']) ? "selected" : "";
                        $options .= <<<HTML
                            <option value="{$data['id']}" {$selected}>
                                {$data['nombre']}
                            </option>
                        HTML;
                    }

                    $dE = base64_encode($row["id"]);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            {$d}
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <select name="id_tipo" class="form-control">
                                {$options}
                            </select>
                        </div>
                    HTML;
                }
            ], [
                "db" => "C.nombre", "as" => "gestiona", "formatter" => function ($d, $row) {
                    include FOLDER_SIDE . "/conn.php";
                    $Tipo = $db->executeQuery(<<<SQL
                    SELECT * FROM HorasExtras_Aprobador_Gestiona
                    SQL);
                    $options = "";
                    foreach ($Tipo as $data) {
                        $selected = ($data['id'] == $row['id_gestiona']) ? "selected" : "";
                        $options .= <<<HTML
                            <option value="{$data['id']}" {$selected}>
                                {$data['nombre']}
                            </option>
                        HTML;
                    }

                    $dE = base64_encode($row["id"]);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            {$d}
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <select name="id_gestiona" class="form-control">
                                {$options}
                            </select>
                        </div>
                    HTML;
                }
            ], [
                "db" => "D.nombre", "as" => "isAdmin", "formatter" => function ($d, $row) {
                    include FOLDER_SIDE . "/conn.php";
                    $Tipo = $db->executeQuery(<<<SQL
                    SELECT * FROM HorasExtras_Aprobador_Administra
                    SQL);
                    $options = "";
                    foreach ($Tipo as $data) {
                        $selected = ($data['id'] == $row['id_Esadmin']) ? "selected" : "";
                        $options .= <<<HTML
                            <option value="{$data['id']}" {$selected}>
                                {$data['nombre']}
                            </option>
                        HTML;
                    }

                    $dE = base64_encode($row["id"]);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            {$d}
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <select name="id_Esadmin" class="form-control">
                                {$options}
                            </select>
                        </div>
                    HTML;
                }
            ], [
                "db" => "E.nombre", "as" => "solicitudPersonal", "formatter" => function ($d, $row) {
                    include FOLDER_SIDE . "/conn.php";
                    $Tipo = $db->executeQuery(<<<SQL
                    SELECT * FROM HorasExtras_Aprobador_SolicitudPersonal
                    SQL);

                    $options = "";
                    foreach ($Tipo as $data) {
                        $selected = ($data['id'] == $row['id_solicitudPersonal']) ? "selected" : "";
                        $options .= <<<HTML
                            <option value="{$data['id']}" {$selected}>
                                {$data['nombre']}
                            </option>
                        HTML;
                    }

                    $dE = base64_encode($row["id"]);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            {$d}
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <select name="id_solicitudPersonal" class="form-control">
                                {$options}
                            </select>
                        </div>
                    HTML;
                }
            ], [
                "db" => "A.id", "formatter" => function ($d, $row) {
                    $dE = base64_encode($d);
                    return <<<HTML
                        <div data-show="{$dE}" class="show">
                            <button type="button" class="rounded btn-info m-1" onclick="ChangeMode({$d})"><i class="fas fa-pen"></i></button>
                        </div>
                        <div data-edit="{$dE}" class="hide">
                            <button type="button" class="rounded btn-success m-1" onclick="ConfirmUpdate({$d}, 'U_Aprobador')"><i class="fas fa-check"></i></button>
                            <button type="button" class="rounded btn-danger m-1" onclick="ChangeMode({$d})"><i class="fas fa-times"></i></button>
                        </div>
                    HTML;
                }
            ]
        ];
        echo json_encode(DataTable::serverSide($_REQUEST, $table, $column, [
            "columns" => "A.*, B.nombre tipo, C.nombre gestiona, D.nombre isAdmin, E.nombre solicitudPersonal"
        ]), JSON_UNESCAPED_UNICODE);
        break;
    default:
        echo json_encode(["error" => "action is undefined"]);
        break;
}
exit();
