<?php
header("Pragma: public");
header("Expires: 0");
$fechaActual = date('d-m-Y');
$filename = "RHE_" . $fechaActual . ".xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

require_once('../../controller/Report.controller.php');

$fechaInicio = '';
$fechaFin = '';
$documentoSoporte = 'Plano Horas Extras';
$html = "<table>
        <tbody>";

if (isset($_POST['fechaInicio'])) {
    $fechaInicio = $_POST['fechaInicio'];
}

if (isset($_POST['fechaFin'])) {
    $fechaFin = $_POST['fechaFin'];
}

try {
    $arrayHE = executeReport($fechaInicio, $fechaFin, 'detalleHoras_2');
    $arrayRecargos = executeReport($fechaInicio, $fechaFin, 'detalleReporte_2');

    foreach ($arrayHE as $items) {
        $html .= "
                <tr>
                <td>" . $items["tipo_horaExtra"] . "</td>
                <td style='width: 160px;'>" . $items["cc"] . "</td>
                <td>" . date('d-m-Y', strtotime($fechaInicio)) . "</td>
                <td>" . date('d-m-Y', strtotime($fechaFin)) . "</td>
                <td>" . date('d-m-Y', strtotime($fechaFin)) . "</td>
                <td style='width: 170px;'>" . $documentoSoporte . "</td>
                <td style='width: 160px;'>0</td>
                <td style='width: 40px;'>4</td>
                <td></td>
                <td style='width: 160px;'></td>";
        $numero = $items["cantidad"];
        $decimal_part = substr(strval($numero), -2);
        $horas = substr(strval($numero), 0, -2);

        if ($decimal_part == '.5') {
            $html .= "
                <td style='width: 45px;'>" . $horas . "</td>
                <td style='width: 45px;'>30</td>";
        } else {
            $html .= "
                <td style='width: 45px;'>" . $horas . "</td>
                <td style='width: 45px;'>0</td>";
        }
        $html .= "
                <td style='width: 160px;'>OCASIONAL</td>
                ";

        $html .= "</tr>";
    }

    foreach ($arrayRecargos as $itemsRec) {
        $html .= "
                <tr>
                <td>" . $itemsRec["tipo_recargo"] . "</td>
                <td style='width: 160px;'>" . $itemsRec["cc"] . "</td>
                <td>" . date('d-m-Y', strtotime($fechaInicio)) . "</td>
                <td>" . date('d-m-Y', strtotime($fechaFin)) . "</td>
                <td>" . date('d-m-Y', strtotime($fechaFin)) . "</td>
                <td style='width: 170px;'>" . $documentoSoporte . "</td>
                <td style='width: 160px;'>0</td>
                <td style='width: 40px;'>4</td>
                <td></td>
                <td style='width: 160px;'></td>";
        $numero = $itemsRec["cantidad"];
        $decimal_part = substr(strval($numero), -2);
        $horas = substr(strval($numero), 0, -2);

        if ($decimal_part == '.5') {
            $html .= "
                <td style='width: 45px;'>" . $horas . "</td>
                <td style='width: 45px;'>30</td>";
        } else {
            $html .= "
                <td style='width: 45px;'>" . $horas . "</td>
                <td style='width: 45px;'>0</td>";
        }
        $html .= "
                <td style='width: 160px;'>OCASIONAL</td>";

        $html .= "</tr>";
    }
} catch (Exception $e) {
    $html .= $e;
}

$html .= "</tbody>
            </table>";

?>

<?= $html ?>