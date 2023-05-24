<?php
session_start();
require("automaticForm.php");

$config = AutomaticForm::getConfig();
define("MESES", ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]);
$i = 0;

switch ($_GET["ssp"]) {
    case "listEstadoHe":
        $table = "ReportesHE";
        $columns = [
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "cc", "dt" => $i++
            ],
            [
                "db" => "id_ceco", "dt" => $i++, "formatter" => function ($d, $row) {
                    return AutomaticForm::getValueSql($d, "id", "titulo", "CentrosCosto");
                }
            ],
            [
                "db" => "id_ceco", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_clase = AutomaticForm::getValueSql($d, "id", "id_clase", "CentrosCosto");
                    return AutomaticForm::getValueSql($id_clase, "id", "titulo", "Clase");
                }
            ],
            [
                "db" => "fechaFin", "dt" => $i++, "formatter" => function ($d, $row) {
                    return date("Y", strtotime($d));
                }
            ],
            [
                "db" => "fechaFin", "dt" => $i++, "formatter" => function ($d, $row) {
                    return MESES[date("m", strtotime($d)) - 1];
                }
            ],
            [
                "db" => "id_aprobador", "dt" => $i++, "formatter" => function ($d, $row) {
                    return AutomaticForm::getValueSql($d, "id", "nombre", "Aprobadores", [
                        "notResult" => "NA"
                    ]);
                }
            ],
            [
                "db" => "id_estado", "dt" => $i++, "formatter" => function ($d, $row) {
                    return AutomaticForm::getValueSql($d, "id", "nombre", "Estados");
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $r = "";
                    $id_estado = AutomaticForm::getValueSql($d, "id", "id_estado", "ReportesHE");
                    $edicion = [2, 6, 8, 10, 1002];
                    // contentPage(page, title, scripts = undefined)
                    $r .= in_array($id_estado, $edicion) ? '<button type="button" class="rounded btn-primary m-1" onclick="contentPage(' . "'reportar/index.view?edit={$d}', 'Editar Reporte #{$d}', 'reporteHE'" . ')"><i class="fa fa-pen"></i></button>' : '';
                    $r .= '<button type="button" class="rounded btn-danger m-1"><i class="fa fa-trash"></i></button>';
                    $r .= '<button type="button" class="rounded btn-info m-1" onclick="showinfo(' . $d . ')" data-toggle="modal" data-target="#viewDetail"><i class="fa fa-eye"></i></button>';
                    return $r;
                }
            ]
        ];
        break;
    case 'listAprobar':
        $idAprobador = AutomaticForm::getValueSql($_SESSION["email"], "correo", "id", "Aprobadores");
        $_GET["where"] = "id_aprobador = {$idAprobador} and (id_estado <> 1 and id_estado <> 2)";
        $table = "ReportesHE";
        $columns = [
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $status = AutomaticForm::getValueSql($d, "id", "checkStatus", "ReportesHE", ["notResult" => 1]);
                    return '<span data-ident="' . $d . '" data-status="' . $status . '">' . $d . '</span>';
                }
            ],
            [
                "db" => "cc", "dt" => $i++
            ],
            [
                "db" => "mes", "dt" => $i++, "formatter" => function ($d, $row) {
                    return date("Y", strtotime($d));
                }
            ],
            [
                "db" => "mes", "dt" => $i++, "formatter" => function ($d, $row) {
                    return MESES[date("m", strtotime($d)) - 1];
                }
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "id", "dt" => $i++
            ]
        ];
        break;
    default: // respuesta negativa del datatable -- muestra algun error
        $table = "";
        $columns = [];
        break;
}

$sql_details = [
    "user" => $config->USER_DB,
    "pass" => $config->PASS_DB,
    "db"   => $config->DATABASE,
    "host" => $config->SERVER_DB,
    "gestor" => "sqlsrv"
];

// codigo de datatable 
// Nota: lo modifique un poco para que funcionara con sql server y mysql
require($config->FOLDER_SITE . "ssp/ssp.class.php");

$primaryKey = AutomaticForm::getNamePrimary($table); // llave primaria de la tabla

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns),
    JSON_UNESCAPED_UNICODE
);