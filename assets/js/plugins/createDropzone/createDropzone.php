<?php

session_start();
include("../../../../controller/automaticForm.php");

$config = AutomaticForm::getConfig();


switch ($_GET["action"]) {
    case 'processData':
        // $af = new AutomaticForm(
        //     (isset($_FILES["file"]) ? $_FILES : ["file" => $_FILES]),
        //     $_GET["table"]
        // );

        $_POST["data"]["usuario"] = $_SESSION["usuario"];
        $af = new AutomaticForm(
            (isset($_FILES["file"]) ? array_merge($_POST, $_FILES) : ["file" => $_FILES, "data" => $_POST["data"]]),
            $_GET["table"]
        );

        echo json_encode($af->execute());
        exit();
        break;
    case 'preview':
        $return = [];
        $data = AutomaticForm::getDataSql($_GET["table"], "usuario = '{$_SESSION["usuario"]}'");

        foreach (glob("{$config->FOLDER_SITE}files/adjuntosHE/*") as $key => $value) {
            $return[] = [
                "name" => basename($value),
                "size" => filesize($value),
                "dirname" => str_replace($config->FOLDER_SITE, $config->URL_SITE, dirname($value)) . "/" . basename($value)
            ];
        }
        // foreach ($data as $key => $value) {
        //     $return = [
        //         "name" => basename($value["adjuntos"]),
        //         // "size" => filesize($value["adjuntos"]),
        //         "dirname" => dirname($value["adjuntos"])
        //     ];
        // }
        echo json_encode($return, JSON_UNESCAPED_UNICODE);
        break;
    default:
        echo json_encode(["Error" => "action is undefined"]);
        exit();
        break;
}
