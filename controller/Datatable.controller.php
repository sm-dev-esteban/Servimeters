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
    case 'listAprobar':
        $table = "ReportesHE A
        inner join Estados B on A.id_estado = B.id
        inner join CentrosCosto C on A.id_ceco = C.id
        ";

        $columns = [
            [
                "db" => "A.id"
            ],
            [
                "db" => "A.cc"
            ],
            [
                "db" => "A.fecha", "formatter" => function ($d) {
                    return date("Y", strtotime($d));
                }
            ],
            [
                "db" => "A.fecha", "formatter" => function ($d) {
                    return date("F", strtotime($d));
                }
            ],
            [
                "db" => "A.empleado"
            ],
            [
                "db" => "B.nombre"
            ],
            [
                "db" => "id"
            ],
            [
                "db" => "id"
            ],
            [
                "db" => "id"
            ]
        ];

        $config["isJoin"] = true;

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
    DataTable::serverSide(
        $_REQUEST,
        $table,
        $columns,
        $config ?? []
    ),
    JSON_UNESCAPED_UNICODE
);
