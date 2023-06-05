<?php
session_start();
include("../../../../controller/automaticForm.php");


$config = AutomaticForm::getConfig();

switch ($_GET["accion"]) {
    case 'create':
        $create = new AutomaticForm(
            [
                "data" => [
                    $_GET["option_value"] => "Default value"
                ]
            ],
            $_GET["table"]
        );
        $create->execute();
        exit();
        break;
    case 'ssp':
        define("TABLE", $_GET["table"]);
        define("PRIMARYKEY", AutomaticForm::getNamePrimary(TABLE));
        define("OPTIONVAL", $_GET["option_value"]);
        define("TOKEN", $_GET["token"]);

        $i = 0;

        $columns = [
            [
                "db" => OPTIONVAL, "dt" => $i++, "formatter" => function ($d, $row) {
                    $id_table = $row["id"];
                    return '
                        <div data-show="' . $id_table . '">' . $d . '</div>
                        <div data-edit="' . $id_table . '" class="d-none">
                            <input class="form-control" value="' . $d . '" onchange="jQuery(this).smModeEdit(`' . TOKEN . '`, ' . $id_table . ', `' . OPTIONVAL . '`, `' . TABLE . '`)">
                        </div>
                    ';
                }
            ],
            [
                "db" => PRIMARYKEY, "dt" => $i++, "formatter" => function ($d, $row) {
                    $x = str_replace("=", "", base64_encode($d));
                    return '
                        <div class="form-group clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="checkbox" id="' . $x . '" value="' . $d . '" onchange="jQuery(this).smChangeMode(`' . TOKEN . '`, ' . $d . ')">
                                <label for="' . $x . '"></label>
                            </div>
                        </div>
                    ';
                }
            ]
        ];

        $sql_details = [
            "user" => $config->USER_DB,
            "pass" => $config->PASS_DB,
            "db"   => $config->DATABASE,
            "host" => $config->SERVER_DB,
            "gestor" => "sqlsrv"
        ];

        require($config->FOLDER_SITE . "ssp/ssp.class.php");

        echo json_encode(
            SSP::simple($_GET, $sql_details, TABLE, PRIMARYKEY, $columns),
            JSON_UNESCAPED_UNICODE
        );
        exit();
        break;
    case 'submit':
        $af = new AutomaticForm($_POST, $_GET["table"]);
        echo json_encode($af->execute(), JSON_UNESCAPED_UNICODE);
        break;
    default:
        echo json_encode(
            ["error" => "action is undefined"],
            JSON_UNESCAPED_UNICODE
        );
        exit();
        break;
}
