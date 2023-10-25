<?php session_start();
/*-- 2023-09-11 21:11:10 --*/

use Controller\AutomaticForm;
use Model\DataTable;
use Model\ProcessData;

include_once "C:/xampp/htdocs/MVC/vendor/autoload.php";
include "C:/xampp/htdocs/MVC/Config.php";
include "C:/xampp/htdocs/MVC/conn.php";

// ini_set('upload_max_filesize', MAX_SIZE);
// ini_set('post_max_size', MAX_SIZE);

$automatic = new AutomaticForm();

switch ($_REQUEST["action"] ?? false) {
    case 'INSERT':
        $allData = array_merge($_REQUEST, $_FILES);

        $response = $automatic::insert("ReportesHE", $allData);

        $idReportesHE = $response["id"] ?? "null";
        $arrayHE = [];

        if (!$response["error"]) {
            # Historial
            $automatic::insert("HorasExtras_Historial_Reportes", [
                "data" => [
                    "id_reporte" => $idReportesHE,
                    "descripcion" => "Se genera el reporte inicial"
                ]
            ]);

            # Comentario
            $automatic::insert("HorasExtras_Historial_Reportes", [
                "data" => [
                    "id_reporte" => $idReportesHE,
                    "descripcion" => "Se genera el reporte inicial"
                ]
            ]);

            $arrayHE = [];
            unset($_POST["HorasExtra"]["id"]);
            foreach ($_POST["HorasExtra"] as $k => $v) { // todo lo que este dentro de este arreglo se va para el detalle 
                $i = 0;
                if (is_array($v)) foreach ($v as $k1 => $v1) {
                    unset($arrayHE[$i]["id"]);
                    $arrayHE[$i]["id_reporteHE"] = $idReportesHE;
                    $arrayHE[$i][$k] = $v1;
                    $i++;
                }
            }

            for ($i = 0; $i < count($arrayHE); $i++) $automatic::insert("HorasExtra", ["data" => $arrayHE[$i]]);
            echo json_encode(["status" => true, "error" => false]);
        } else echo json_encode(["status" => false, "error" => "failed insert data"]);
        break;
    case 'UPDATE':
        $id = base64_decode($_REQUEST["report"]);
        $allData = array_merge($_REQUEST, $_FILES);

        $response = $automatic::update("ReportesHE", $allData, ["id" => $id]);
        $idReportesHE = $id;
        $arrayHE = [];
        $editarHE = [];

        if (!$response["error"]) {
            # Historial
            $automatic::insert("HorasExtras_Historial_Reportes", [
                "data" => [
                    "id_reporte" => $idReportesHE,
                    "descripcion" => "Modificaciones en el reporte"
                ]
            ]);

            $db->executeQuery(<<<SQL
            delete from HorasExtra where id_reporteHE = :id
            SQL, [":id" => $id]);

            $arrayHE = [];
            $editarHE = [];
            foreach ($_POST["HorasExtra"] as $k => $v) { // todo lo que este dentro de este arreglo se va para el detalle 
                $i = 0;
                if (is_array($v)) foreach ($v as $k1 => $v1) {
                    $arrayHE[$i]["id_reporteHE"] = $idReportesHE;
                    if ($k <> "id") $arrayHE[$i][$k] = $v1;
                    else $editarHE[$i] = $v1;
                    $i++;
                }
            }

            for ($i = 0; $i < count($arrayHE); $i++) $automatic::insert("HorasExtra", ["data" => $arrayHE[$i]]);
            echo json_encode(["status" => true, "error" => false]);
        } else echo json_encode(["status" => false, "error" => "failed update data"]);
        break;
    case 'ssp_horas':

        $table[] = "ReportesHE RHE";
        $table[] = "INNER JOIN CentrosCosto CECO on CECO.id = RHE.id_ceco";
        $table[] = "INNER JOIN Clase C on C.id = CECO.id_clase";
        $table[] = "LEFT JOIN Aprobadores A on A.id = RHE.id_aprobador";
        $table[] = "INNER JOIN HorasExtras_Estados E on E.id = RHE.id_estado";

        $config = [];
        $columns = [
            [
                "db" => "RHE.id",
            ], [
                "db" => "RHE.CC",
            ], [
                "db" => "CECO.titulo", "as" => "centroCosto"
            ], [
                "db" => "C.titulo", "as" => "clase"
            ], [
                "db" => "RHE.mes", "formatter" => function ($d) {
                    return date("Y", strtotime($d));
                }
            ], [
                "db" => "RHE.mes", "formatter" => function ($d) {
                    return date("M", strtotime($d));
                }
            ], [
                "db" => "A.nombre", "as" => "aprobador", "failed" => <<<HTML
                <b class="text-danger">No Registra</b>
                HTML
            ], [
                "db" => "E.nombre",
            ], [
                "db" => "RHE.id", "formatter" => function ($d) {
                    $SERVER_SIDE = SERVER_SIDE;
                    $idE = base64_encode($d);
                    return <<<HTML
                        <button class="rounded btn-primary m-1" onclick="location.href='{$SERVER_SIDE}/horasExtras/editarHoras?report={$idE}'" type="button">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button class="rounded btn-warning m-1" type="button">
                            <i class="fa fa-history"></i>
                        </button>
                        <button class="rounded btn-info m-1" type="button" data-mode="reportHE" data-toggle="modal" data-target="#modalMain" data-id="{$idE}">
                            <i class="fa fa-eye"></i>
                        </button>
                    HTML;
                }
            ]
        ];
        // $config["condition"] = "correoEmpleado like '%{$_SESSION["email"]}%'";

        echo json_encode(DataTable::serverSide($_REQUEST, $table, $columns));
        break;
    case 'getReportesHE':
    case 'getHorasExtra':
        $id = base64_decode($_REQUEST["id"]);
        $table = [
            "getReportesHE" => "ReportesHE WHERE id = '{$id}'",
            "getHorasExtra" => "HorasExtra WHERE id_reporteHE = '{$id}'",
        ][$_REQUEST["action"]];
        echo json_encode($db->executeQuery(<<<SQL
        SELECT * FROM {$table}
        SQL));
        break;
    case 'ssp_gestion':
        $table[] = "ReportesHE RHE";
        $table[] = "INNER JOIN HorasExtras_Estados E on RHE.id_estado = E.id";
        $table[] = "INNER JOIN CentrosCosto CECO on RHE.id_ceco = CECO.id";
        $table[] = "INNER JOIN Clase C on CECO.id_clase = C.id";

        $config["columns"] = "RHE.*, E.nombre estado, C.titulo clase, CECO.titulo centroCosto";
        $config["condition"] = "RHE.id_aprobador = '{$_SESSION["id"]}'";

        $columns = [
            [
                "db" => "RHE.id"
            ], [
                "db" => "RHE.CC"
            ], [
                "db" => "RHE.mes", "formatter" => function ($d, $row) {
                    return date("Y", strtotime($d));
                }
            ], [
                "db" => "RHE.mes", "formatter" => function ($d, $row) {
                    return date("M", strtotime($d));
                }
            ], [
                "db" => "RHE.reportador_por"
            ], [
                "db" => "E.nombre", "as" => "estado", "formatter" => function ($d, $row) {
                    $checked = $row["id_aprobador_checked"];
                    $checked = ($checked == $_SESSION["id"] ? "true" : "false");
                    return <<<HTML
                        <span data-check={$checked}>{$d}</span>
                    HTML;
                }
            ], [
                "db" => "C.titulo", "as" => "clase"
            ], [
                "db" => "CECO.titulo", "as" => "centroCosto"
            ], [
                "db" => "RHE.id", "formatter" => function ($d, $row) {
                    $idE = base64_encode($d);
                    return <<<HTML
                        <button type="button" class="rounded btn-info m-1" data-mode="reportHE" data-toggle="modal" data-target="#modalMain" data-id="{$idE}">
                            <i class="fa fa-eye"></i>
                        </button>
                    HTML;
                }
            ]
        ];
        echo json_encode(DataTable::serverSide($_REQUEST, $table, $columns, $config));
        break;
    case 'gestion_checked':
        echo json_encode(["status" => $db->executeQuery(<<<SQL
            -- no recordaba como hacer un if en sql y le pedi ayuda con la consulta
            -- chatgpt
            UPDATE ReportesHE
            SET id_aprobador_checked = CASE 
                WHEN id_aprobador_checked = 0 THEN {$_SESSION["id"]}
                WHEN id_aprobador_checked = {$_SESSION["id"]} THEN 0
                ELSE {$_SESSION["id"]}
            END
            WHERE id = :ID;
            -- chatgpt
        SQL, [
            ":ID" => base64_decode($_POST["report"])
        ])]);
        break;
    case 'seleccionar_horas':
    case 'deseleccionar_horas':
        $estados = $db->executeQuery(<<<SQL
            select * from HorasExtras_Estados
        SQL);

        $whereType = [
            "JEFE" => filter($estados, "nombre", "APROBACION_JEFE"),
            "GERENTE" => filter($estados, "nombre", "APROBACION_GERENTE")
        ][$_SESSION["type"]] ?? false;

        $whereManages = [
            "RH" => filter($estados, "nombre", "APROBACION_RH"),
            "CONTABLE" => filter($estados, "nombre", "APROBACION_CONTABLE")
        ][$_SESSION["manages"]] ?? false;

        $where = implode(" or ", array_map(function ($x) {
            return "id_estado = '{$x}'";
        }, [
            $whereType[0]["id"],
            $whereManages[0]["id"]
        ]));

        $update = [
            "seleccionar_horas" => "id_aprobador_checked = {$_SESSION["id"]}",
            "deseleccionar_horas" => "id_aprobador_checked = 0",
        ][$_GET["action"]];

        $query = <<<SQL
            UPDATE ReportesHE SET {$update} where id_aprobador = {$_SESSION["id"]} and ({$where})
        SQL;

        echo json_encode([
            "status" => $db->executeQuery($query),
            "query" => $query
        ]);

        break;
    case 'pendientes':
        $query = <<<SQL
            SELECT RHE.*, HEE.nombre estado FROM ReportesHE RHE
            INNER JOIN HorasExtras_Estados HEE ON HEE.id = RHE.id_estado
            WHERE RHE.id_aprobador_checked = :APROBADOR1 AND id_aprobador = :APROBADOR2
        SQL;

        echo json_encode($db->executeQuery($query, [
            ":APROBADOR1" => $_SESSION["id"],
            ":APROBADOR2" => $_SESSION["id"]
        ]));
        break;
    case 'aprobar_horas':
        $approvers = $_POST["aprobadores"] ?? [];
        $id_approver = $_SESSION["id"] ?? false;

        if (!empty(count($approvers)) && $id_approver) {

            $approvers = array_merge([
                ":GERENTE" => 0,
                ":RH" => 0,
                ":CONTABLE" => 0
            ], $approvers);

            $estados = $db->executeQuery(<<<SQL
                SELECT * FROM HorasExtras_Estados
            SQL);

            $statusArray = [
                # Jefe
                "A_J" => filter($estados, "nombre", "APROBACION_JEFE")[0],
                "R_G" => filter($estados, "nombre", "RECHAZO_GERENTE")[0],
                # Gerente
                "A_G" => filter($estados, "nombre", "APROBACION_GERENTE")[0],
                "R_R" => filter($estados, "nombre", "RECHAZO_RH")[0],
                "R_C" => filter($estados, "nombre", "RECHAZO_CONTABLE")[0],
                # RH
                "A_R" => filter($estados, "nombre", "APROBACION_RH")[0],
                # Contable
                "A_C" => filter($estados, "nombre", "APROBACION_CONTABLE")[0],
                # Aprobado
                "A" => filter($estados, "nombre", "APROBADO")[0]
            ];

            $query = <<<SQL
                UPDATE ReportesHE
                SET id_estado = CASE
                    -- Gerente
                    WHEN id_estado = {$statusArray["A_J"]["id"]} THEN {$statusArray["A_G"]["id"]}
                    WHEN id_estado = {$statusArray["R_G"]["id"]} THEN {$statusArray["A_G"]["id"]}
                    -- RH
                    WHEN id_estado = {$statusArray["A_G"]["id"]} THEN {$statusArray["A_R"]["id"]}
                    WHEN id_estado = {$statusArray["R_R"]["id"]} THEN {$statusArray["A_R"]["id"]}
                    WHEN id_estado = {$statusArray["R_C"]["id"]} THEN {$statusArray["A_R"]["id"]}
                    -- Contable
                    WHEN id_estado = {$statusArray["A_R"]["id"]} THEN {$statusArray["A_C"]["id"]}
                    -- Aprobado
                    WHEN id_estado = {$statusArray["A_C"]["id"]} THEN {$statusArray["A"]["id"]}
                    ELSE id_estado
                END,
                id_aprobador = CASE
                    -- Gerente
                    WHEN id_estado = {$statusArray["A_J"]["id"]} THEN {$approvers[":GERENTE"]}
                    WHEN id_estado = {$statusArray["R_G"]["id"]} THEN {$approvers[":GERENTE"]}
                    -- RH
                    WHEN id_estado = {$statusArray["A_G"]["id"]} THEN {$approvers[":RH"]}
                    WHEN id_estado = {$statusArray["R_R"]["id"]} THEN {$approvers[":RH"]}
                    WHEN id_estado = {$statusArray["R_C"]["id"]} THEN {$approvers[":RH"]}
                    -- Contable
                    WHEN id_estado = {$statusArray["A_R"]["id"]} THEN {$approvers[":CONTABLE"]}
                    ELSE id_aprobador
                END,
                id_aprobador_checked = 0
                WHERE id_aprobador_checked = {$id_approver} AND id_aprobador = {$id_approver}
            SQL;

            echo json_encode([
                "approvers" => $approvers,
                "query" => $query,
                "status" => $db->executeQuery($query)
            ], JSON_UNESCAPED_UNICODE);
        } else
            echo json_encode(["status" => false], JSON_UNESCAPED_UNICODE);
        break;
    case 'rechazar_horas':
        $type = $_GET["type"] ?? false;
        $estados = $db->executeQuery(<<<SQL
            SELECT * FROM HorasExtras_Estados
        SQL);

        $statusArray = [
            "R" => filter($estados, "nombre", "RECHAZO")[0],
            "R_G" => filter($estados, "nombre", "RECHAZO_GERENTE")[0],
            "R_R" => filter($estados, "nombre", "RECHAZO_RH")[0],
            "R_C" => filter($estados, "nombre", "RECHAZO_CONTABLE")[0]
        ];

        foreach ($_POST["rechazar"] as $data) {
            $automatic->insert("HorasExtras_Comentario", [
                "data" => [
                    "titulo" => "Rechazo por {$_SESSION["usuario"]}",
                    "cuerpo" => $_POST["motivo"] ?? "",
                    "id_reporte" => $data["id"],
                    "id_tipoComentario" => "2"
                ]
            ]);

            $processData = new ProcessData([
                "data" => [
                    "id_aprobador" => $_POST["aprobador"] ?? "0",
                    "id_aprobador_checked" => "0",
                    "id_estado" => [
                        "EMPLEADO" => $statusArray["R"]["id"],
                        "JEFE" => $statusArray["R_G"]["id"],
                        "GERENTE" => $statusArray["R_R"]["id"],
                        "RH" => $statusArray["R_C"]["id"],
                    ][strtoupper($type)] ?? "0"
                ]
            ], "ReportesHE", "UPDATE", ["id" => $data["id"]]);
            $processData->execute();
        }
        echo json_encode(["status" => true], JSON_UNESCAPED_UNICODE);
        break;
    case 'approvedRequest':
        echo json_encode($db->executeQuery(<<<SQL
            SELECT A.* FROM Aprobadores A 
            INNER JOIN HorasExtras_Aprobador_Tipo HAT ON HAT.id = A.id_tipo
            INNER JOIN HorasExtras_Aprobador_Gestiona HAG ON HAG.id = A.id_gestiona
            WHERE HAT.nombre LIKE :FIND1 OR HAG.nombre LIKE :FIND2
        SQL, [
            ":FIND1" => "%{$_POST["find"]}%",
            ":FIND2" => "%{$_POST["find"]}%"
        ]));
        break;
    case 'ya va que toy bloqueao':
        # code...
        break;
    case 'Excel1':
    case 'Excel2':
        $fechaI = $_POST["fechaInicio"] ?? "";
        $fechaF = $_POST["fechaFin"] ?? "";
        $query = <<<SQL
            SELECT
            RHE.*,
            CECO.titulo ceco,
            C.titulo clase,
            HEE.nombre estado,
            A.nombre aprobador
            FROM ReportesHE RHE
            INNER JOIN CentrosCosto CECO        ON CECO.id  = RHE.id_ceco
            INNER JOIN Clase C                  ON C.id     = CECO.id_clase
            INNER JOIN HorasExtras_Estados HEE  ON HEE.id   = RHE.id_estado
            INNER JOIN Aprobadores A            ON A.id     = RHE.id_aprobador
            WHERE RHE.fecha_inicio >= :FI AND RHE.fecha_fin <= :FF
        SQL;

        echo json_encode($db->executeQuery($query, [
            ":FI" => $fechaI,
            ":FF" => $fechaF
        ]));
        break;
    default:
        echo json_encode(["error" => "action is undefined"]);
        break;
}


function filter($array, $column, $filter)
{
    return array_values(array_filter($array, function ($x) use ($column, $filter) {
        return $x[$column] == $filter;
        // return $x[$column] ? strpos(strtoupper($x[$column]), strtoupper($filter)) : false;
    }));
}
