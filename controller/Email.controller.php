<?php

require_once('../config/SendEmail.config.php');

$email = new Email();


switch ($_GET['email']) {
    case 'solicitudEmpleado':
        $to = $_POST['to'];
        $from = $_POST['from'];

        $empleado = $_POST['empleado'];
        $reporteHE = $_POST['idReporte'];

        $Subject = 'Solicitud revision de Horas Extra para ' . $empleado;
        $body = 'Buen dia, Tiene una solicitud de Horas Extra con el número ' . $reporteHE . ' pendiente por revisar. ' . '. Este mensaje ha sido generado automáticamente.';

        $result = $email->sendEmail($to, $from, $Subject, $body);
        echo $result;
        exit();
        break;
    case 'actualizacionHE':
        $to = $_POST['to'];
        $from = $_POST['from'];

        $empleado = $_POST['empleado'];
        $reporteHE = $_POST['idReporte'];

        $Subject = 'Actualizacion Horas Extra por ' . $empleado;
        $body = 'Buen dia, Las Horas Extra con el número ' . $reporteHE . ' han sido actualizadas y estan pendiente por revisar. ' . '. Este mensaje ha sido generado automáticamente.';

        $result = $email->sendEmail($to, $from, $Subject, $body);
        echo $result;
        exit();
        break;
    case 'reporteNovedad':
        $to = $_POST['to'];
        $from = $_POST['from'];

        $empleado = $_POST['empleado'];
        $reporteHE = $_POST['idHE'];
        $novedad = $_POST['novedad'];

        $Subject = 'Novedad de Horas Extra por ' . $empleado;
        $body = 'Buen dia, Tiene una novedad sobre las Horas Extra con el número ' . $reporteHE . '. Novedad: ' . $novedad . ' Este mensaje ha sido generado automáticamente.';

        $result = $email->sendEmail($to, $from,  $Subject, $body);
        echo $result;
        exit();

        break;
    case 'rechazoHE':
        $result = '';

        $datas = $_POST["data"];
        foreach ($datas as $data) {
            $obj = json_decode($data);
            $to = $obj->to;
            $from = $obj->from;
            if (isset($obj->cc)) {
                $cc = $obj->cc;
            } else {
                $cc = $from;
            }
            $empleado =  $obj->empleado;
            $reporteHE =  $obj->idReporte;
            $motivo =  $obj->motivo;

            $Subject = 'Rechazo de Horas Extra por ' . $empleado;
            $body = 'Buen dia, Las Horas Extra con el número ' . $reporteHE . ' han sido rechazados. ' . $motivo . '. Este mensaje ha sido generado automáticamente.';

            $result = $email->sendEmail($to, $cc, $Subject, $body);
        }

        echo $result;
        exit();

        break;
    case 'aprobacionHE':
        $result = '';

        $datas = $_POST["data"];
        foreach ($datas as $data) {
            $obj = json_decode($data);
            $to = $obj->to;
            $from = $obj->from;
            $reporteHE =  $obj->idReporte;

            $Subject = 'Aprobación de Horas Extra';
            $body = 'Buen dia, Las Horas Extra con el número ' . $reporteHE . ' han sido aprobadas. Este mensaje ha sido generado automáticamente.';

            $result = $email->sendEmail($to, $from, $Subject, $body);
        }
        echo $result;
        exit();
        break;
    case 'aprobacionMasiva':
        $to = $_POST['to'];
        $from = $_POST['from'];

        $Subject = 'Horas Extra aprobadas';
        $body = 'Buen dia, el usuario ' . $from . ' ha aprobado un lote de Horas Extra, por favor validar. Este mensaje ha sido generado automáticamente.';

        $result = $email->sendEmail($to, $from, $Subject, $body);
        echo $result;
        exit();
        break;
    case 'rechazoMasivo':
        $to = $_POST['to'];
        $from = $_POST['from'];
        $motivo = $_POST['motivo'];

        $Subject = 'Horas Extra rechazadas';
        $body = 'Buen dia, el usuario ' . $from . ' ha rechazado un lote de Horas Extra. Motivo: ' . $motivo . '. Este mensaje ha sido generado automáticamente.';

        $result = $email->sendEmail($to, $from, $Subject, $body);
        echo $result;
        exit();
        break;
    default:
        echo '';
        break;
}
