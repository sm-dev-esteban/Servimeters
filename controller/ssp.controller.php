<?php
session_start();
require("automaticForm.php");

$config = AutomaticForm::getConfig();
define("CONFIG", $config);
define("MESES", ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]);
$i = 0;

// echo json_encode($_GET, JSON_UNESCAPED_UNICODE);
// exit();

switch ($_GET["ssp"]) {
    case "listEstadoHe":
        $_GET["where"] = "empleado = '{$_SESSION["usuario"]}'";
        $table = "ReportesHE";
        define("TABLE", $table);

        $columns = [
            [
                "db" => "id", "dt" => $i++
            ],
            [
                "db" => "cc", "dt" => $i++
            ],
            [
                "db" => "id_ceco", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return AutomaticForm::getValueSql($d, "@primary", "titulo", "CentrosCosto");
                }
            ],
            [
                "db" => "id_ceco", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $id_clase = AutomaticForm::getValueSql($d, "@primary", "id_clase", "CentrosCosto");
                    return AutomaticForm::getValueSql($id_clase, "@primary", "titulo", "Clase");
                }
            ],
            [
                "db" => "fechaFin", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return date("Y", strtotime($d));
                }
            ],
            [
                "db" => "fechaFin", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return MESES[date("m", strtotime($d)) - 1];
                }
            ],
            [
                "db" => "id_aprobador", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return AutomaticForm::getValueSql($d, "@primary", "nombre", "Aprobadores", [
                        "notResult" => "NA"
                    ]);
                }
            ],
            [
                "db" => "id_estado", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return AutomaticForm::getValueSql($d, "@primary", "nombre", "Estados");
                }
            ],
            [
                "db" => "id_estado", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $edicion = [CONFIG->RECHAZO, CONFIG->EDICION];
                    $r = "";
                    $r .= in_array($d, $edicion) ? '<button type="button" class="rounded btn-primary m-1" onclick="contentPage(' . "'reportar/index.view?edit={$id_table}', 'Editar Reporte #{$id_table}', 'reporteHE'" . ')"><i class="fa fa-pen"></i></button>' : '';
                    // $r .= '<button type="button" class="rounded btn-danger m-1"><i class="fa fa-trash"></i></button>';
                    $r .= '<button type="button" class="rounded btn-warning m-1" data-toggle="modal" data-target="#modalComments' . date("Y") . '" data-id="' . $id_table . '"><i class="fas fa-history"></i></button>';
                    $r .= '<button type="button" class="rounded btn-info m-1" onclick="showinfo(' . $id_table . ')" data-toggle="modal" data-target="#viewDetail"><i class="fa fa-eye"></i></button>';
                    return $r;
                }
            ]
        ];
        break;
    case "listAprobar":
        $idAprobador = AutomaticForm::getValueSql($_SESSION["email"], "correo", "id", "Aprobadores");

        $rol = strtolower($_SESSION["rol"] ?? false);
        $gestion = strtolower($_SESSION["gestion"] ?? false);

        define("GESTION", $gestion);
        define("ROL", $rol);

        $flujo = [
            "jefe" => [
                $config->APROBACION_JEFE,
                $config->RECHAZO_GERENTE
            ],
            "gerente" => [
                $config->APROBACION_GERENTE,
                $config->RECHAZO_RH,
                $config->RECHAZO_CONTABLE
            ],
            "rh" => $config->APROBACION_RH,
            "contable" => $config->APROBACION_CONTABLE
        ];

        $id_estado = implode(" OR ", array_map(function ($vs) {
            if (!is_array($vs))
                return "id_estado = '{$vs}'";
            return implode(" OR ", array_map(function ($vs_s) {
                return "id_estado = '{$vs_s}'";
            }, $vs));
        }, array_filter($flujo, function ($fv) {
            return $fv == GESTION || $fv == ROL;
        }, ARRAY_FILTER_USE_KEY)));

        $_GET["where"] = "({$id_estado})" . (in_array(ROL, array_keys($flujo)) ? " and id_aprobador = '{$idAprobador}'" : "");

        $table = "ReportesHE";
        define("TABLE", $table);

        $columns = [
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $status = AutomaticForm::getValueSql($d, "@primary", "check_user", "ReportesHE", [
                        "notResult" => 0
                    ]);
                    return '<span data-ident="' . $d . '" data-status="' . $status . '">' . $d . '</span>';
                }
            ],
            [
                "db" => "cc", "dt" => $i++
            ],
            [
                "db" => "mes", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return date("Y", strtotime($d));
                }
            ],
            [
                "db" => "mes", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return MESES[date("m", strtotime($d)) - 1];
                }
            ],
            [
                "db" => "empleado", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return $d;
                }
            ],
            [
                "db" => "id_estado", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $estado = AutomaticForm::getValueSql($d, "@primary", "nombre", "Estados");
                    return $estado;
                }
            ],
            [
                "db" => "id_ceco", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $id_clase = AutomaticForm::getValueSql($d, "@primary", "id_clase", "CentrosCosto");
                    $clase = AutomaticForm::getValueSql($id_clase, "@primary", "titulo", "Clase");
                    return $clase;
                }
            ],
            [
                "db" => "id_ceco", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $ceco = AutomaticForm::getValueSql($d, "@primary", "titulo", "CentrosCosto");
                    return $ceco;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $descuento = AutomaticForm::getValueSql($d, "id_reporteHE", "descuento", "HorasExtra");
                    return $descuento + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $E_Diurna_Ord = AutomaticForm::getValueSql($d, "id_reporteHE", "E_Diurna_Ord", "HorasExtra");
                    return $E_Diurna_Ord + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $E_Nocturno_Ord = AutomaticForm::getValueSql($d, "id_reporteHE", "E_Nocturno_Ord", "HorasExtra");
                    return $E_Nocturno_Ord + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $E_Diurna_Fest = AutomaticForm::getValueSql($d, "id_reporteHE", "E_Diurna_Fest", "HorasExtra");
                    return $E_Diurna_Fest + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $E_Nocturno_Fest = AutomaticForm::getValueSql($d, "id_reporteHE", "E_Nocturno_Fest", "HorasExtra");
                    return $E_Nocturno_Fest + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $R_Nocturno = AutomaticForm::getValueSql($d, "id_reporteHE", "R_Nocturno", "HorasExtra");
                    return $R_Nocturno + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $R_Fest_Diurno = AutomaticForm::getValueSql($d, "id_reporteHE", "R_Fest_Diurno", "HorasExtra");
                    return $R_Fest_Diurno + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $R_Fest_Nocturno = AutomaticForm::getValueSql($d, "id_reporteHE", "R_Fest_Nocturno", "HorasExtra");
                    return $R_Fest_Nocturno + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $R_Ord_Fest_Noct = AutomaticForm::getValueSql($d, "id_reporteHE", "R_Ord_Fest_Noct", "HorasExtra");
                    return $R_Ord_Fest_Noct + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $total = AutomaticForm::getValueSql($d, "id_reporteHE", "total", "HorasExtra");
                    return $total + 0;
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return '<button class="btn btn-warning" data-toggle="modal" data-target="#modalComments' . date("Y") . '" data-id="' . $id_table . '"><i class="fa fa-clock"></i></button>';
                }
            ]
        ];
        break;
    case "clase":
        $table = "Clase";
        define("TABLE", $table);

        $columns = [
            [
                "db" => "titulo", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return '
                    <div data-show="' . $id_table . '">' . $d . '</div>
                    <div data-edit="' . $id_table . '"class="d-none">
                        <input data-column="titulo" data-update="' . $id_table . '" data-table="' . TABLE . '" type="text" value="' . $d . '" class="form-control" onchange="update(this)">
                    </div>
                    ';
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    return '
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="' . base64_encode($d) . '" value="' . $d . '" onchange="ChangeMode(' . $d . ')">
                            <label for="' . base64_encode($d) . '"></label>
                        </div>
                    </div>
                    ';
                }
            ]
        ];
        break;
    case "ceco":
        $table = "CentrosCosto";
        define("TABLE", $table);

        $columns = [
            [
                "db" => "titulo", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return '
                    <div data-show="' . $id_table . '">' . $d . '</div>
                    <div class="d-none" data-edit="' . $id_table . '">
                        <input data-column="titulo" data-update="' . $id_table . '" data-table="' . TABLE . '" type="text" value="' . $d . '" class="form-control" onchange="update(this)">
                    </div>
                    ';
                }
            ],
            [
                "db" => "id_clase", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $clases = AutomaticForm::getDataSql("Clase");
                    $clase = AutomaticForm::getValueSql($d, "@primary", "titulo", "Clase");
                    $option = "";
                    foreach ($clases as $key => $value) {
                        $option .= '
                        <option value="' . $value["id"] . '" ' . ($d == $value["id"] ? 'selected' : '') . '>
                            ' . $value["titulo"] . '
                        </option>
                        ';
                    }
                    return '
                    <div data-show="' . $id_table . '">' . $clase . '</div>
                    <div class="d-none" data-edit="' . $id_table . '">
                        <select data-column="id_clase" data-update="' . $id_table . '" data-table="' . TABLE . '" class="form-control" onchange="update(this)">
                            ' . $option . '
                        </select>
                    </div>
                    ';
                }
            ],
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return '
                    <div class="form-group clearfix">
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="' . base64_encode($d) . '" value="' . $d . '" onchange="ChangeMode(' . $d . ')">
                            <label for="' . base64_encode($d) . '"></label>
                        </div>
                    </div>
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

// Nota: lo modifique un poco para que funcionara con sql server y mysql
require($config->FOLDER_SITE . "ssp/ssp.class.php");

$primaryKey = AutomaticForm::getNamePrimary($table); // llave primaria de la tabla


echo json_encode(
    SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns),
    JSON_UNESCAPED_UNICODE
);
