<?php
require("automaticForm.php");
$config = AutomaticForm::getConfig();

switch ($_GET["action"]) {
    case 'registroHE':
        $data = array_merge(isset($_POST) ? $_POST : [], isset($_FILES) ? $_FILES : []);
        $ReportesHE = new AutomaticForm($data, "ReportesHE", $_POST["action"], $_POST["edit"]);
        $checkRHE = $ReportesHE->execute(true, true);
        if (isset($checkRHE["error"])) { // si ocurre un error (fijo puede pasar con sql server) sale y muestra el error
            echo json_encode(["query" => $checkRHE, "error" => $checkRHE["error"]], JSON_UNESCAPED_UNICODE);
            exit();
        }
        $arrayHE = [];
        $editarHe = [];
        foreach ($_POST["HorasExtra"] as $key => $value) { // todo lo que este dentro de este arreglo se va para el detalle 
            $i = -1;
            if (is_array($value)) {
                foreach ($value as $key1 => $value1) {
                    $i++;
                    if ($key <> "id") {
                        $arrayHE[$i][$key] = $value1;
                        $arrayHE[$i]["id_reporteHE"] = $ReportesHE->getId();
                        $arrayHE[$i]["total"] = $_POST["data"]["total"];
                    } else {
                        $editarHe[$i] = $value1;
                    }
                }
            } else {
                // ni la menor idea de que puede recibir en esta parte
            }
        }
        for ($i = 0; $i < count($arrayHE); $i++) {
            $HorasExtra = new AutomaticForm(["data" => $arrayHE[$i]], "HorasExtra", $_POST["action"], $editarHe[$i]);
            $checkRE = $HorasExtra->execute(true, true);
            if (isset($checkRE["error"])) { // si ocurre un error (fijo puede pasar con sql server) sale y muestra el error
                echo json_encode(["query" => $checkRE, "error" => $checkRE["error"]], JSON_UNESCAPED_UNICODE);
                exit();
            }
        }
        echo json_encode(["status" => $checkRHE["status"]], JSON_UNESCAPED_UNICODE);
        exit;
        break;
    case 'permiso':
        $permiso = new AutomaticForm($_POST, "Permisos");
        echo json_encode($permiso->execute());
        break;
    default:
        echo json_encode(["Error" => "action is undefined"], JSON_UNESCAPED_UNICODE);
        exit;
        break;
}
