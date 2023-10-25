<?php session_start();
/*-- 2023-09-22 09:16:22 --*/

use Controller\AutomaticForm;
use Model\DataTable;
use Model\Email;

include_once "C:/xampp/htdocs/MVC/vendor/autoload.php";
include "C:/xampp/htdocs/MVC/Config.php";
include "C:/xampp/htdocs/MVC/conn.php";

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
                    if ($action == "ssp_solicitudes") {
                        $res[] = <<<HTML
                            <button class="rounded btn-info m-1" type="button" data-mode="reportSP" data-toggle="modal" data-target="#modalMain" data-id="{$id}">
                                <i class="fa fa-eye"></i>
                            </button>
                        HTML;
                        if ($row["estado"] == "Aprobado jefe") $res[] = <<<HTML
                            <button class="rounded btn-info m-1" type="button" onclick="location.href = `{$SERVER}/solicitudPersonal/solicitud/cargarHojasDeVida?report={$id}`">
                                <i class="fa fa-file"></i>
                            </button>
                        HTML;
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
                        $res[] = <<<HTML
                            <button class="rounded btn-info m-1" type="button" data-mode="reportSP" data-toggle="modal" data-target="#modalMain" data-id="{$id}">
                                <i class="fa fa-eye"></i>
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
    default:
        echo json_encode(["error" => "action is undefined"]);
        break;
}
