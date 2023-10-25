<?php

class ChartController
{
    private const MONTHS = [
        "M_1" => "Enero",
        "M_2" => "Febrero",
        "M_3" => "Marzo",
        "M_4" => "Abril",
        "M_5" => "Mayo",
        "M_6" => "Junio",
        "M_7" => "Julio",
        "M_8" => "Agosto",
        "M_9" => "Septiembre",
        "M_10" => "Octubre",
        "M_11" => "Noviembre",
        "M_12" => "Diciembre"
    ];

    private function order($colum, $order = "ASC")
    {
        return function ($a, $b) use ($colum, $order) {
            return [
                "ASC" => $a[$colum] > $b[$colum],
                "DESC" => $a[$colum] < $b[$colum]
            ][strtoupper($order)] ?? strnatcmp($a[$colum], $b[$colum]);
        };
    }

    private function getCECO(): array
    {
        return [];
    }

    public function getDataChart()
    {
    }

    public function getChart($option, $type, $json_encode = false): mixed
    {
        switch ($option) {
            case 'value':
                break;
            default:
                throw new Exception("Chart is not define", 1);
                break;
        }

        $json = [
            "labels" => $Chart["labels"] ?? [],
            "label" => $Chart["label"] ?? [],
            "data" => $Chart["data"] ?? [],
            "knob" => $Chart["knob"] ?? [],
            "option" => $option,
            "type" => $type
        ];

        return $json_encode === true ? json_encode($json, JSON_UNESCAPED_UNICODE) : $json;
    }
}
