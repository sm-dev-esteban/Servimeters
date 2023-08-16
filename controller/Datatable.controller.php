<?php
include_once(dirname(__DIR__) . "/model/Datatable.php");

switch ($_REQUEST["ssp"] ?? false) {
    case 'ceco':
        $table = "CentrosCosto CC
        inner join Clase C on CC.id_clase = C.id
        ";

        $columns = [
            [
                "db" => "CC.titulo", "as" => "ceco", "formatter" => function ($d, $row) {
                    return '
                    <div data-show="' . $row["id"] . '">' . $d . '</div>
                    <div data-edit="' . $row["id"] . '" class="d-none">
                        <input data-column="titulo" data-update="' . $row["id"] . '" data-table="CentrosCosto" type="text" value="' . $d . '" class="form-control" onchange="updateClass(this)">
                    </div>
                    ';
                }
            ],
            [
                "db" => "C.titulo", "as" => "clase", "formatter" => function ($d, $row) {
                    $clases = AutomaticForm::getDataSql("Clase");
                    $option = "";
                    foreach ($clases as $value)
                        $option .= '
                        <option value="' . $value["id"] . '" ' . ($value["titulo"] == $d ? "selected" : "") . '>
                            ' . $value["titulo"] . '
                        </option>
                        ';

                    return '
                    <div data-show="' . $row["id"] . '">' . $d . '</div>
                    <div data-edit="' . $row["id"] . '" class="d-none">
                        <select data-column="id_clase" data-update="' . $row["id"] . '" data-table="CentrosCosto" class="form-control" onchange="updateClass(this)">
                            ' . $option . '
                        </select>
                    </div>
                    ';
                }
            ],
            [
                "db" => "CC.id", "formatter" => function ($d) {
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
                "db" => "nombre", "formatter" => function ($d, $row) {
                    return '
                    <div data-show="' . $row["id"] . '">' . $d . '</div>
                    <div data-edit="' . $row["id"] . '" class="d-none">
                        <input type="text" class="form-control" value="' . $d . '" onchange="update(this)" data-column="nombre" data-update="' . $row["id"] . '" data-table="' . TABLE . '">
                    </div>
                    ';
                }
            ], [
                "db" => "correo", "formatter" => function ($d, $row) {
                    return '
                    <div data-show="' . $row["id"] . '">' . $d . '</div>
                    <div data-edit="' . $row["id"] . '" class="d-none">
                        <input type="text" class="form-control" value="' . $d . '" onchange="update(this)" data-column="correo" data-update="' . $row["id"] . '" data-table="' . TABLE . '">
                    </div>
                    ';
                }
            ], [
                "db" => "tipo", "formatter" => function ($d, $row) {
                    $option = "";
                    foreach (["NA", "Jefe", "Gerente"] as $key => $value) {
                        $option .= '<option value="' . $value . '" ' . ($value == $d ? "selected" : "") . '>' . $value . '</option>';
                    }
                    return '
                    <div data-show="' . $row["id"] . '">' . $d . '</div>
                    <div data-edit="' . $row["id"] . '" class="d-none">
                        <select class="form-control" onchange="update(this)" data-column="tipo" data-update="' . $row["id"] . '" data-table="' . TABLE . '">' . $option . '</select>
                    </div>
                    ';
                }
            ], [
                "db" => "gestiona", "formatter" => function ($d, $row) {
                    $option = "";
                    foreach (["NA", "RH", "Contable"] as $key => $value) {
                        $option .= '<option value="' . $value . '" ' . ($value == $d ? "selected" : "") . '>' . $value . '</option>';
                    }
                    return '
                    <div data-show="' . $row["id"] . '">' . $d . '</div>
                    <div data-edit="' . $row["id"] . '" class="d-none">
                        <select class="form-control" onchange="update(this)" data-column="gestiona" data-update="' . $row["id"] . '" data-table="' . TABLE . '">' . $option . '</select>
                    </div>
                    ';
                }
            ], [
                "db" => "esAdmin", "formatter" => function ($d, $row) {
                    $option = "";
                    foreach (["No", "Si"] as $key => $value) {
                        $option .= '<option value="' . $value . '" ' . ($value == $d ? "selected" : "") . '>' . $value . '</option>';
                    }
                    return '
                    <div data-show="' . $row["id"] . '">' . $d . '</div>
                    <div data-edit="' . $row["id"] . '" class="d-none">
                        <select class="form-control" onchange="update(this)" data-column="esAdmin" data-update="' . $row["id"] . '" data-table="' . TABLE . '">' . $option . '</select>
                    </div>
                    ';
                }
            ], [
                "db" => "id", "formatter" => function ($d, $row) {
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
    case 'listEstadoHe':
        $table = "ReportesHE A
        inner join CentrosCosto B on A.id_ceco = B.id
        inner join Clase C on B.id_clase = C.id
        inner join Aprobadores D on A.id_aprobador = D.id
        inner join Estados E on A.id_estado = E.id
        ";

        $columns = [
            [
                "db" => "A.id"
            ], [
                "db" => "A.cc"
            ], [
                "db" => "B.titulo", "as" => "ceco"
            ], [
                "db" => "C.titulo", "as" => "clase"
            ], [
                "db" => "A.fechaFin", "formatter" => function ($d, $row) {
                    return date("Y", strtotime($d));
                }
            ], [
                "db" => "A.fechaFin", "formatter" => function ($d, $row) {
                    return date("M", strtotime($d));
                }
            ], [
                "db" => "D.nombre", "as" => "aprobador"
            ], [
                "db" => "E.nombre", "as" => "estado"
            ], [
                "db" => "id_estado", "formatter" => function ($d, $row) {
                    $config = AutomaticForm::getConfig();
                    $edicion = [$config->RECHAZO, $config->EDICION];
                    $r = "";
                    $r .= in_array($d, $edicion) ? '<button type="button" class="rounded btn-primary m-1" onclick="contentPage(' . "'reportar/index.view?edit={$row["id"]}', 'Editar Reporte #{$row["id"]}', 'reporteHE'" . ')"><i class="fa fa-pen"></i></button>' : '';
                    // $r .= '<button type="button" class="rounded btn-danger m-1"><i class="fa fa-trash"></i></button>';
                    $r .= '<button type="button" class="rounded btn-warning m-1" data-toggle="modal" data-target="#modalComments' . date("Y") . '" data-id="' . $row["id"] . '"><i class="fas fa-history"></i></button>';
                    $r .= '<button type="button" class="rounded btn-info m-1" onclick="showinfo(' . $row["id"] . ')" data-toggle="modal" data-target="#viewDetail"><i class="fa fa-eye"></i></button>';
                    return $r;
                }
            ]
        ];
        break;
    case 'listAprobar':
        $table = "ReportesHE A
        inner join Estados B on A.id_estado = B.id
        inner join CentrosCosto C on A.id_ceco = C.id
        ";

        $columns = [
            [
                "db" => "A.id"
            ], [
                "db" => "A.cc"
            ], [
                "db" => "A.fecha", "formatter" => function ($d) {
                    return date("Y", strtotime($d));
                }
            ], [
                "db" => "A.fecha", "formatter" => function ($d) {
                    return date("F", strtotime($d));
                }
            ], [
                "db" => "A.empleado"
            ], [
                "db" => "B.nombre"
            ], [
                "db" => "id"
            ], [
                "db" => "id"
            ], [
                "db" => "id"
            ]
        ];

        break;
    case 'listSolicitud':
        $table = "solicitudPersonal";
        $columns = [
            [
                "db" => "id"
            ], [
                "db" => "fechaRegistro", "formatter" => function ($d) {
                    return date("Y-m-d H:i:s", strtotime($d));
                }
            ], [
                "db" => "solicitadoPor"
            ], [
                "db" => "id"
            ], [
                "db" => "id"
            ], [
                "db" => "id"
            ], [
                "db" => "id"
            ]
        ];
        break;
    case 'listSolicitudRechazada':
        $table = "solicitudPersonal sP
        inner join requisicion_proceso rP on rP.id = sP.id_proceso";
        $columns = [
            [
                "db" => "sP.id"
            ], [
                "db" => "rP.nombre"
            ], [
                "db" => "sP.nombreCargo"
            ], [
                "db" => "sP.id"
            ]
        ];
        break;
    default:
        echo json_encode(
            [
                "Error" => "action is undefined"
            ],
            JSON_UNESCAPED_UNICODE
        );
        exit();
        break;
}

echo json_encode(
    DataTable::serverSide($_REQUEST, $table, $columns, $config ?? []),
    JSON_UNESCAPED_UNICODE
);
