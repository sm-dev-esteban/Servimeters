<?php
require("automaticForm.php");
$config = AutomaticForm::getConfig();

if ($_GET["t"] ?? false) date_default_timezone_set($_GET["t"]);

$action = $_GET["action"] ?? "";
$data = array_merge($_REQUEST ?? [], $_FILES ?? []);

$request = [$data, $action];

switch ($action) {
    case 'registroHE':
        $ReportesHE = new AutomaticForm($data, "ReportesHE", $_POST["action"], $_POST["edit"]);
        $checkRHE = $ReportesHE->execute(true, true);
        if (isset($checkRHE["error"])) { // si ocurre un error (fijo puede pasar con sql server) sale y muestra el error
            echo json_encode(["query" => $checkRHE, "error" => $checkRHE["error"]], JSON_UNESCAPED_UNICODE);
            exit();
        }
        $arrayHE = [];
        $editarHE = [];
        foreach ($_POST["HorasExtra"] as $k => $v) { // todo lo que este dentro de este arreglo se va para el detalle 
            $i = 0;
            if (is_array($v)) foreach ($v as $k1 => $v1) {
                if ($k <> "id") {
                    $arrayHE[$i][$k] = $v1;
                    $arrayHE[$i]["id_reporteHE"] = $ReportesHE->getId();
                    $arrayHE[$i]["total"] = $_POST["data"]["total"];
                } else $editarHE[$i] = $v1;

                $i++;
            }
        }
        // $detalle = json_decode($data["detalle"]);
        // foreach ($detalle as $k => $v) {
        //     $DetallesHoraExtra = new AutomaticForm(["data" => array_merge(get_object_vars($v), ["id_horaExtra" => $ReportesHE->getId()])], "DetallesHoraExtra");
        //     echo json_encode($DetallesHoraExtra->execute(), JSON_UNESCAPED_UNICODE);
        // }
        for ($i = 0; $i < count($arrayHE); $i++) {
            $HorasExtra = new AutomaticForm(["data" => $arrayHE[$i]], "HorasExtra", $_POST["action"], $editarHE[$i]);
            $checkRE = $HorasExtra->execute(true, true);
            if (isset($checkRE["error"])) { // si ocurre un error (fijo puede pasar con sql server) sale y muestra el error
                echo json_encode(["query" => $checkRE, "error" => $checkRE["error"]], JSON_UNESCAPED_UNICODE);
                exit();
            }
        }
        echo json_encode(["status" => $checkRHE["status"]], JSON_UNESCAPED_UNICODE);
        exit;
        break;
    default:
        $af = new AutomaticForm(...$request);
        echo json_encode($af->execute(), JSON_UNESCAPED_UNICODE);
        exit;
        break;
}
