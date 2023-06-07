<?php
session_start();
require("automaticForm.php");

$config = AutomaticForm::getConfig();
define("CONFIG", $config);
define("MESES", ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]);
$i = 0;

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
                    $r = "";
                    $edicion = [CONFIG->RECHAZO, CONFIG->EDICION];
                    $r .= in_array($d, $edicion) ? '<button type="button" class="rounded btn-primary m-1" onclick="contentPage(' . "'reportar/index.view?edit={$d}', 'Editar Reporte #{$d}', 'reporteHE'" . ')"><i class="fa fa-pen"></i></button>' : '';
                    $r .= '<button type="button" class="rounded btn-danger m-1"><i class="fa fa-trash"></i></button>';
                    $r .= '<button type="button" class="rounded btn-info m-1" onclick="showinfo(' . $d . ')" data-toggle="modal" data-target="#viewDetail"><i class="fa fa-eye"></i></button>';
                    return $r;
                }
            ]
        ];
        break;
    case 'listAprobar':
        $idAprobador = AutomaticForm::getValueSql($_SESSION["email"], "correo", "id", "Aprobadores");
        $rol = strtolower(isset($_SESSION["rol"]) ? $_SESSION["rol"] : false);
        // $rol = AutomaticForm::getSession("rol");
        $estado = [
            "jefe" => $config->APROBACION_JEFE,
            "gerente" => $config->APROBACION_GERENTE,
            "rh" => $config->APROBACION_RH,
            "contable" => $config->APROBACION_CONTABLE
        ];
        $id_estado = $rol ? $estado[$rol] : $rol;
        // $_GET["where"] = "id_aprobador = {$idAprobador} and (id_estado <> 1 and id_estado <> 2)";
        // $_GET["where"] = "id_aprobador = {$idAprobador} and id_estado = {$id_estado}";
        // $_GET["where"] = "id_aprobador = {$idAprobador} or id_estado = {$id_estado}";
        $_GET["where"] = "id_estado = {$id_estado} and id_aprobador = {$idAprobador}";
        $table = "ReportesHE";
        define("TABLE", $table);

        $columns = [
            [
                "db" => "id", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $status = AutomaticForm::getValueSql($d, "@primary", "checkStatus", "ReportesHE", [
                        "notResult" => 1
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
                    return '<button class="btn btn-info"><i class="fa fa-print"></i></button>';
                }
            ]
        ];
        break;
    case 'clase':
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
    case 'ceco':
        $table = "CentrosCosto CC inner join Clase C on CC.id_clase = C.id";
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
    case 'aprobadores':
        $table = "Aprobadores";
        define("TABLE", $table);
        $columns = [
            [
                "db" => "nombre", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return '
                    <div data-show="' . $id_table . '">' . $d . '</div>
                    <div data-edit="' . $id_table . '" class="d-none">
                        <input type="text" class="form-control" value="' . $d . '" onchange="update(this)" data-column="nombre" data-update="' . $id_table . '" data-table="' . TABLE . '">
                    </div>
                    ';
                }
            ],
            [
                "db" => "correo", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $d = AutomaticForm::getValueSql($id_table, "@primary", "correo", TABLE);
                    return '
                    <div data-show="' . $id_table . '">' . $d . '</div>
                    <div data-edit="' . $id_table . '" class="d-none">
                        <input type="text" class="form-control" value="' . $d . '" onchange="update(this)" data-column="correo" data-update="' . $id_table . '" data-table="' . TABLE . '">
                    </div>
                    ';
                }
            ],
            [
                "db" => "tipo", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $option = "";
                    foreach (["NA", "Jefe", "Gerente"] as $key => $value) {
                        $option .= '<option value="' . $value . '" ' . ($value == $d ? "selected" : "") . '>' . $value . '</option>';
                    }
                    return '
                    <div data-show="' . $id_table . '">' . $d . '</div>
                    <div data-edit="' . $id_table . '" class="d-none">
                        <select class="form-control" onchange="update(this)" data-column="tipo" data-update="' . $id_table . '" data-table="' . TABLE . '">' . $option . '</select>
                    </div>
                    ';
                }
            ],
            [
                "db" => "gestiona", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $option = "";
                    foreach (["NA", "RH", "Gerente"] as $key => $value) {
                        $option .= '<option value="' . $value . '" ' . ($value == $d ? "selected" : "") . '>' . $value . '</option>';
                    }
                    return '
                    <div data-show="' . $id_table . '">' . $d . '</div>
                    <div data-edit="' . $id_table . '" class="d-none">
                        <select class="form-control" onchange="update(this)" data-column="gestiona" data-update="' . $id_table . '" data-table="' . TABLE . '">' . $option . '</select>
                    </div>
                    ';
                }
            ],
            [
                "db" => "esAdmin", "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    $option = "";
                    foreach (["No", "Si"] as $key => $value) {
                        $option .= '<option value="' . $value . '" ' . ($value == $d ? "selected" : "") . '>' . $value . '</option>';
                    }
                    return '
                    <div data-show="' . $id_table . '">' . $d . '</div>
                    <div data-edit="' . $id_table . '" class="d-none">
                        <select class="form-control" onchange="update(this)" data-column="esAdmin" data-update="' . $id_table . '" data-table="' . TABLE . '">' . $option . '</select>
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
