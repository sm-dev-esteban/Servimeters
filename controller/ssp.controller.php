<?php
require("automaticForm.php");
$config = AutomaticForm::getConfig();

define("MESES", ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]);

switch ($_GET["ssp"]) {
    case "listEstadoHe":
        $i = 0;
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
                    return AutomaticForm::getValue($d, "id", "titulo", "CentrosCosto");
                }
            ],
            [
                "db" => "id_ceco", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_clase = AutomaticForm::getValue($d, "id", "id_clase", "CentrosCosto");
                    return AutomaticForm::getValue($id_clase, "id", "titulo", "Clase");
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
                    return AutomaticForm::getValue($d, "id", "nombre", "Aprobadores", [
                        "notResult" => "NA"
                    ]);
                }
            ],
            [
                "db" => "id_estado", "dt" => $i++, "formatter" => function ($d, $row) {
                    return AutomaticForm::getValue($d, "id", "nombre", "Estados");
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    return '
                    <button type="button" class="btn btn-primary m-1"><i class="fa fa-pen"></i></button>
                    <button type="button" class="btn btn-danger m-1"><i class="fa fa-trash"></i></button>
                    <button type="button" class="btn btn-info m-1" onclick="showinfo(' . $d . ')" data-toggle="modal" data-target="#viewDetail"><i class="fa fa-eye"></i></button>
                    ';
                }
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
require("{$config->FOLDER_SITE}ssp/ssp.class.php");

$primaryKey = AutomaticForm::getNamePrimary($table); // llave primaria de la tabla

echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns),
    JSON_UNESCAPED_UNICODE
);
