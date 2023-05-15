<?php
require("automaticForm.php");
$config = AutomaticForm::getConfig();

// faltaban los estados >:(
// foreach ( [
//     "APROBADO",
//     "RECHAZO",
//     "APROBACION_JEFE",
//     "APROBACION_GERENTE",
//     "RECHAZO_GERENTE",
//     "APROBACION_RH",
//     "RECHAZO_RH",
//     "APROBACION_CONTABLE",
//     "RECHAZO_CONTABLE",
//     "EDICION"
// ] as $key => $value) {
//     $db = new DB();
//     $conn = $db->Conectar();
//     $query = $conn->prepare("
//     SET IDENTITY_INSERT Estados ON
//     DBCC CHECKIDENT ('dbo.Estados', RESEED, " . ($config->$value - 1) . ")
//     SET IDENTITY_INSERT Estados OFF
//     INSERT INTO Estados (nombre) values ('{$value}')
//     ");
//     $query->execute();
// }

switch ($_GET["action"]) {
    case 'registroHE':
        // (["data" => [], "file" => ""])
        $ReportesHE = new AutomaticForm(array_merge($_POST, $_FILES), "ReportesHE");
        $checkRHE = $ReportesHE->execute(true, true);
        if (isset($checkRHE["error"])) { // si ocurre un error (fijo puede pasar con sql server) sale y muestra el error
            echo json_encode(["query" => $checkRHE, "error" => $checkRHE["error"]], JSON_UNESCAPED_UNICODE);
            exit();
        }
        $arrayHE = [];
        foreach ($_POST["HorasExtra"] as $key => $value) { // todo lo que este dentro de este arreglo se va para el detalle 
            $i = -1;
            foreach ($value as $key1 => $value1) {
                $i++;
                $arrayHE[$i][$key] = $value1;
                $arrayHE[$i]["id_reporteHE"] = $ReportesHE->getId();
                $arrayHE[$i]["total"] = $_POST["data"]["total"];
            }
        }
        for ($i = 0; $i < count($arrayHE); $i++) {
            $HorasExtra = new AutomaticForm(["data" => $arrayHE[$i]], "HorasExtra");
            $checkRE = $HorasExtra->execute(true, true);
            if (isset($checkRE["error"])) { // si ocurre un error (fijo puede pasar con sql server) sale y muestra el error
                echo json_encode(["query" => $checkRE, "error" => $checkRE["error"]], JSON_UNESCAPED_UNICODE);
                exit();
            }
        }
        // echo json_encode($ReportesHE->execute(), JSON_UNESCAPED_UNICODE);
        echo json_encode(["status" => $checkRHE["status"]], JSON_UNESCAPED_UNICODE);
        exit;
        break;
    default:
        echo json_encode(["Error" => "action is undefined"], JSON_UNESCAPED_UNICODE);
        exit;
        break;
}
