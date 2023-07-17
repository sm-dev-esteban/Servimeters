<?php

// session_start();
include_once(__DIR__ . "/automaticForm.php");

define("MESES", [
    1 => "Enero",
    2 => "Febrero",
    3 => "Marzo",
    4 => "Abril",
    5 => "Mayo",
    6 => "Junio",
    7 => "Julio",
    8 => "Agosto",
    9 => "Septiembre",
    10 => "Octubre",
    11 => "Noviembre",
    12 => "Diciembre"
]);

define("CECO", AutomaticForm::getDataSql("CentrosCosto"));

function order($column, $order_by = "ASC")
{
    return function ($a, $b) use ($column, $order_by) {
        $r = [
            "ASC" => $a[$column] > $b[$column],
            "DESC" => $a[$column] < $b[$column]
        ];
        return $r[$order_by] ?? strnatcmp($a[$column], $b[$column]);
    };
}

if (isset($_GET["chart"])) echo getChart($_GET["chart"], $_GET["type"] ?? "line", true);

function getChart($switch, $type, $json_encode = false)
{
    $Chart = [];
    $config = AutomaticForm::getConfig();
    $correo = $_SESSION["email"] ?? false;

    switch ($switch) {
        case 'he_anual':
            $Chart["label"] = "Horas reportadas";

            $m = number_format(date("m")) + 1;
            $y = date("Y") - 1;

            $HEaprob = AutomaticForm::getDataSql(
                "ReportesHE",
                "correoEmpleado = '{$correo}' and id_estado = '{$config->APROBADO}'",
                "count(*) count"
            );
            $HErecha = AutomaticForm::getDataSql(
                "ReportesHE",
                "correoEmpleado = '{$correo}' and id_estado = '{$config->RECHAZO}'",
                "count(*) count"
            );
            $HEgener = AutomaticForm::getDataSql(
                "ReportesHE",
                "correoEmpleado = '{$correo}' and id_estado <> '{$config->APROBADO}' and id_estado <> '{$config->RECHAZO}'",
                "count(*) count"
            );

            $arrayknob = [
                $HEaprob[0]["count"] ?? 0,
                $HErecha[0]["count"] ?? 0,
                $HEgener[0]["count"] ?? 0
            ];

            $HEtotal = array_sum($arrayknob ?? [0]); // 100%

            for ($i = 0; $i < count($arrayknob); $i++) $Chart["knob"][] = [
                "data" => round($HEtotal > 0 ? $arrayknob[$i] * 100 / $HEtotal : 0, 2)
            ];

            for ($i = 0; $i < count(MESES); $i++) {
                if ($m == 13) {
                    $m = 1; // Enero
                    $y = $y + 1; // Año actual
                }
                // $Chart["labels"][] = MESES[$m] . " - {$y}";
                $Chart["labels"][] = MESES[$m];
                $m = str_pad($m, 2, 0, STR_PAD_LEFT);
                $TotalMeses = AutomaticForm::getDataSql(
                    "HorasExtra HE inner join ReportesHE RHE on HE.id_reporteHE = RHE.id",
                    // "HE.fecha like '%{$y}-{$m}%' and RHE.correoEmpleado = '{$correo}'",
                    "HE.fecha like '%{$y}-{$m}%'",
                    "sum(HE.total) count",
                    ["checkTableExists" => false]
                );

                $Chart["data"][] = $TotalMeses[0]["count"] ?? 0;
                $m++; // Cambia de mes
            }
            break;
        case 'he_ceco':
            $Chart["label"] = "Reportes";

            $ceco = [];

            foreach (CECO as $data) {
                $totalCeco = AutomaticForm::getDataSql(
                    "ReportesHE",
                    "id_ceco = '{$data["id"]}'",
                    "count(*) count",
                    ["checkTableExists" => false]
                );
                if (!empty($totalCeco[0]["count"] ?? 0)) {
                    $Chart["labels"][] = $data["titulo"];
                    $Chart["data"][] = $totalCeco[0]["count"];
                }
            }

            $cecoTotal = array_sum($Chart["data"] ?? [0]);
            foreach ($Chart["data"] ?? [] as $key => $value) {
                $Chart["knob"][] = [
                    "title" => "% {$Chart["labels"][$key]}",
                    "data" => round($value > 0 ? $value * 100 / $cecoTotal : 0, 2)
                ];
            }

            usort($Chart["knob"], order("data", "DESC"));

            break;
    }

    $json = [
        "labels" => $Chart["labels"] ?? [],
        "label" => $Chart["label"] ?? [],
        "data" => $Chart["data"] ?? [],
        "knob" => $Chart["knob"] ?? [],
        "canvas" => $switch,
        "type" => $type
    ];

    return ($json_encode === true ?
        json_encode($json, JSON_UNESCAPED_UNICODE) :
        $json
    );
    exit();
}
