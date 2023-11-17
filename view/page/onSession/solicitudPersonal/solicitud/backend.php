<?php session_start();
/*-- 2023-09-22 09:16:22 --*/

use Controller\AutomaticForm;
use Controller\SeeApplicationReport;
use Model\DataTable;
use Model\Email;

include_once "C:/xampp/htdocs/servimeters/vendor/autoload.php";
include "C:/xampp/htdocs/servimeters/Config.php";
include "C:/xampp/htdocs/servimeters/conn.php";

date_default_timezone_set(TIMEZONE);

$af = new AutomaticForm();
$mail = new Email();

$action = $_GET["action"] ?? false;

switch ($action) {
    case 'I_requisicion':
        $data = $_POST;
        $response = $af->insert("solicitudPersonal", $data);
        if ($response["status"]) $response = array_merge($response, ["mail" => $mail->sendEmail(
            $data["data"]["emailsolicitadoPor"],
            $_SESSION["mail"],
            "Prueba",
            "Prueba de envio de correo"
        )]);

        echo json_encode($response);
        break;
    case 'ssp_solicitudes':
    case 'ssp_aprobacion':
        $config = [];

        if ($action === "ssp_aprobacion") $config["condition"] = "estado = 1";

        $table[] = "solicitudPersonal A";
        $table[] = "inner join requisicion_proceso B on A.id_proceso = B.id";
        $table[] = "inner join requisicion_estado C on A.estado = C.id";

        $columns = [
            [
                "db" => "A.id"
            ], [
                "db" => "B.nombre"
            ], [
                "db" => "A.nombreCargo"
            ], [
                "db" => "C.nombre", "as" => "estado", "formatter" => function ($d) {
                    return $d;
                }
            ], [
                "db" => "A.id", "formatter" => function ($d, $row) use ($action) {
                    $SERVER = SERVER_SIDE;
                    $res = [];
                    $id = base64_encode($d);

                    $res[] = <<<HTML
                        <button class="rounded btn-info m-1" type="button" data-mode="reportSP" data-toggle="modal" data-target="#modalMain" data-id="{$id}">
                            <i class="fa fa-eye"></i>
                        </button>
                    HTML;

                    if ($action == "ssp_solicitudes") {
                        if ($row["estado"] == "Aprobado jefe") $res[] = <<<HTML
                            <button class="rounded btn-info m-1" type="button" onclick="location.href = `{$SERVER}/solicitudPersonal/solicitud/cargarHojasDeVida?report={$id}`">
                                <i class="fa fa-file"></i>
                            </button>
                        HTML;

                        if (!in_array($row["estado"], [
                            "Pendiente",
                            "Rechazo jefe",
                            "Rechazado",
                            "Cancelado"
                        ])) $res[] = <<<HTML
                            <button class="rounded btn-info m-1" type="button" onclick="location.href = `{$SERVER}/solicitudPersonal/solicitud/agregarCandidatos?report={$id}`">
                                <i class="fa fa-user-plus"></i>
                            </button>
                        HTML;
                        // http://localhost/Servimeters/solicitudPersonal/solicitud/seleccionarCandidatos
                    } else {
                        $res[] = <<<HTML
                            <button class="rounded btn-success m-1" type="button" onclick="aprobar_rechazar('{$id}', 'aprobar')">
                                <i class="fa fa-check"></i>
                            </button>
                        HTML;
                        $res[] = <<<HTML
                            <button class="rounded btn-danger m-1" type="button" onclick="aprobar_rechazar('{$id}', 'rechazar')">
                                <i class="fa fa-times"></i>
                            </button>
                        HTML;
                    }

                    return implode("", $res);
                }
            ]
        ];
        echo json_encode(DataTable::serverSide($_REQUEST, $table, $columns, $config), JSON_UNESCAPED_UNICODE);
        break;
    case 'aprobar':
    case 'rechazar':
        $estado = [
            "aprobar" => 2,
            "rechazar" => 3,
        ][$action];

        $id = base64_decode($_POST["id"] ?? false);
        $query = <<<SQL
            UPDATE solicitudPersonal set estado = :ESTADO where id = :ID
        SQL;

        if ($action === "rechazar") $af->insert("", [
            "data" => [
                "titulo" => "Rechazo",
                "cuerpo" => ""
            ]
        ]);

        echo json_encode([
            "status" => $db->executeQuery($query, [
                ":ID" => $id,
                ":ESTADO" => $estado
            ])
        ], JSON_UNESCAPED_UNICODE);
        break;
    case 'autoComplete':
        $limit = $_POST["limit"] ?? false;
        $filter = $_POST["filter"] ?? [];
        $search = $_POST["search"] ?? false;

        $columns = implode(", ", $filter);
        $search = str_replace("*", "%", $search);

        $newFilter = implode(" OR ", array_map(function ($f) use ($search) {
            return "{$f} LIKE '{$search}'";
        }, $filter));

        $query = trim(<<<SQL
            SELECT TOP ({$limit}) {$columns} FROM solicitudPersonal WHERE {$newFilter}
        SQL);

        echo json_encode($db->executeQuery($query));
        break;
    case 'agregarCandidato':
        $id = $_POST["requisicion"] ?? 0;
        $res = $af->insert("requisicion_candidatos", [
            "data" => [
                "nombreCompleto" => "",
                "id_requisicion" => $id
            ]
        ]);
        if ($res["status"] === true) {
            echo json_encode($db->executeQuery(trim(<<<SQL
                SELECT * FROM requisicion_candidatos where id = {$res["id"]}
            SQL)));
        }
        break;
    case 'validarRequisicion':
        $id = $_GET["requisicion"] ?? 0;

        $res = $db->executeQuery(trim(<<<SQL
            SELECT count(*) count FROM solicitudPersonal where id = {$id}
        SQL));

        echo json_encode(["status" => !empty($res[0]["count"])]);
        break;
    case 'buscarCandidatos':
    case 'buscarCandidatosCitados':
        $condicion = "1 = 1";
        if ($action === "buscarCandidatosCitados") $condicion = "candidatoCitado = 'true'";

        $id = $_POST["requisicion"] ?? 0;

        echo json_encode($db->executeQuery(trim(<<<SQL
            SELECT * FROM requisicion_candidatos where id_requisicion = {$id} and {$condicion}
        SQL)));
        break;
    case 'buscarReporte':
        $id = $_POST["requisicion"] ?? 0;
        echo SeeApplicationReport::viewApplicationReport(base64_encode($id));
        break;
    case 'actualizarCandidatos':
        $data = $_POST ?? [];
        $id = $_POST["id"] ?? false;

        if (isset($data["data"]["candidatoCitado"]) && (bool)$data["data"]["candidatoCitado"] == true) $data["data"]["fechaCitacion"] = date("Y-m-d H:i:s.v");

        $result = $af->update("requisicion_candidatos", $data, ["id" => $id]);

        unset($result["query"], $result["id"]);

        echo json_encode($result, JSON_UNESCAPED_UNICODE);
        break;
    case 'eliminarCandidato':
        $id = $_GET["id"] ?? 0;
        echo json_encode($db->executeQuery(trim(<<<SQL
            DELETE FROM requisicion_candidatos where id = {$id}
        SQL)));

        break;
    default:
        echo json_encode(["error" => "action is undefined"]);
        break;
}
