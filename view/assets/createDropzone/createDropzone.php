<?php

use Controller\AutomaticForm;

include_once "C:/xampp/htdocs/servimeters/vendor/autoload.php";
include "C:/xampp/htdocs/servimeters/Config.php";
include "C:/xampp/htdocs/servimeters/conn.php";

$action = $_GET["action"] ?? false;

$af = new AutomaticForm();

switch (strtoupper($action)) {
    case 'TEMPLATE':
        $ident = $_POST["ident"] ?? false;
        print <<<HTML
            <div id="actions-{$ident}" class="row">
                <div class="col-lg-6">
                    <div class="btn-group w-100">
                        <span class="btn btn-success col fileinput-button-{$ident}">
                            <i class="fas fa-plus"></i>
                            <span>Agregar Archivos</span>
                        </span>
                        <!-- <button type="submit" class="btn btn-primary col start">
                            <i class="fas fa-upload"></i>
                            <span>Start upload</span>
                        </button> -->
                        <button type="reset" class="btn btn-warning col cancel">
                            <i class="fas fa-times-circle"></i>
                            <span>Borrar archivos</span>
                        </button>
                    </div>
                </div>
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="fileupload-process w-100">
                        <div id="total-progress-{$ident}" class="progress progress-striped active" role="progressbar" aria-valuemin="0"
                            aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table table-striped files" id="previews-{$ident}">
                <div id="template-{$ident}" class="row mt-2">
                    <div class="col-auto">
                        <span class="preview"><img src="data:," alt="" data-dz-thumbnail /></span>
                    </div>
                    <div class="col d-flex align-items-center">
                        <p class="mb-0">
                            <span class="lead" data-dz-name></span>
                            (<span data-dz-size></span>)
                        </p>
                        <strong class="error text-danger" data-dz-errormessage></strong>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <div class="progress progress-striped active w-100" role="progressbar" aria-valuemin="0"
                            aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div class="col-auto d-flex align-items-center">
                        <div class="btn-group">
                            <!-- <button class="btn btn-primary start">
                                <i class="fas fa-upload"></i>
                                <span>Start</span>
                            </button> -->
                            <!-- <button data-dz-remove class="btn btn-warning cancel">
                                <i class="fas fa-times-circle"></i>
                                <span>Cancel</span>
                            </button> -->
                            <button data-dz-remove class="btn btn-danger delete">
                                <i class="fas fa-trash"></i>
                                <span>Borrar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
        break;
    case 'INSERT':
    case 'UPDATE':
        $afAction = strtolower($action);
        $table = $_GET["table"] ?? false;
        $data = array_merge($_REQUEST, $_FILES);
        echo json_encode($af::$afAction($table, $data), JSON_UNESCAPED_UNICODE);
        exit;
        break;
    case 'PREVIEW':
        // $t = $_POST["table"];
        // $i = $_POST["ident"];
        // $p = $_POST["preview"];
        // $s = $_POST["separator"];
        $return = ["hellow"];

        // $data = AutomaticForm::getValueSql($i, "@primary", $p, $t);

        // if (!empty($data)) foreach (explode($s, $data) as $key => $value) {
        //     $v1 = str_replace($config->URL_SITE, $config->FOLDER_SITE, $value);
        //     $v2 = $value;
        //     $return[] = [
        //         "name" => basename($v1),
        //         "size" => filesize($v1),
        //         "dirname" => dirname($v2) . "/" . basename($v1)
        //     ];
        // }


        echo json_encode($return, JSON_UNESCAPED_UNICODE);
        break;
    default:
        echo json_encode(["Error" => "action is undefined"]);
        exit();
        break;
}
