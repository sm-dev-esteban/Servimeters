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
        echo json_encode($permiso->execute(), JSON_UNESCAPED_UNICODE);
        break;
    case 'rechazo':
        $rechazo = new AutomaticForm($_POST, "Comentarios");
        echo json_encode($rechazo->execute(), JSON_UNESCAPED_UNICODE);
        break;
    case 'clase':
        $clase = new AutomaticForm($_POST, "Clase");
        echo json_encode($clase->execute(), JSON_UNESCAPED_UNICODE);
        break;
    case 'ceco':
        $ceco = new AutomaticForm($_POST, "CentrosCosto");
        echo json_encode($ceco->execute(), JSON_UNESCAPED_UNICODE);
        break;
    case 'aprobador':
        $aprobador = new AutomaticForm($_POST, "Aprobadores");
        echo json_encode($aprobador->execute(), JSON_UNESCAPED_UNICODE);
        break;
    default:
        echo json_encode(["error" => "action is undefined"], JSON_UNESCAPED_UNICODE);
        exit;
        break;
}
