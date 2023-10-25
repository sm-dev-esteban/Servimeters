<?php

session_start();
include("../../../../controller/automaticForm.php");

$config = AutomaticForm::getConfig();

switch (strtoupper($_GET["action"])) {
    case 'INSERT':
    case 'UPDATE':

        $_POST["data"]["usuario"] = $_SESSION["usuario"];
        $af = new AutomaticForm(array_merge($_POST, $_FILES), $_GET["table"], $_GET["action"]);

        echo json_encode($af->execute());
        exit();
        break;
    case 'PREVIEW':
        $t = $_POST["table"];
        $i = $_POST["ident"];
        $p = $_POST["preview"];
        $s = $_POST["separator"];
        $return = [];

        $data = AutomaticForm::getValueSql($i, "@primary", $p, $t);

        if (!empty($data)) foreach (explode($s, $data) as $key => $value) {
            $v1 = str_replace($config->URL_SITE, $config->FOLDER_SITE, $value);
            $v2 = $value;
            $return[] = [
                "name" => basename($v1),
                "size" => filesize($v1),
                "dirname" => dirname($v2) . "/" . basename($v1)
            ];
        }


        echo json_encode($return, JSON_UNESCAPED_UNICODE);
        break;
    default:
        echo json_encode(["Error" => "action is undefined"]);
        exit();
        break;
}
