<?php // https://datatables.net/manual/server-side

include_once(dirname(__DIR__) . "/controller/automaticForm.php");

class DataTable
{
    /**
     * @param array $request datos enviados por el datatable
     * @param String $table Nombre de la tabla
     * @param array $columns arreglo con las columnas que se van a mostrar en la tabla
     * @param array $config Configuración adicional.
     * 1. $config["isJoin"]: En el caso de que quiera usar joins enviar en true
     */
    static function serverSide(
        array $request,
        String $table,
        array $columns,
        array $config = []
    ): array {

        $config = array_merge([
            "isJoin" => false,
            "condition" => false,
            "columns" => false
        ], $config);

        if (!$config["isJoin"] && strpos(strtolower($table), " join ") !== false) $config["isJoin"] = true;

        $data = [];
        $search = [":filter" => "%{$request["search"]["value"]}%"];
        $condition = $config["condition"] !== false ? $config["condition"] : "1 = 1";

        /*-- columns --*/
        $showColumn = $config["columns"] !== false ? $config["columns"] : implode(", ", array_map(
            function ($x) {
                return $x["db"] . " " . ($x["as"] ?? "");
            },
            $columns
        ));
        /*-- columns --*/

        /*-- filter --*/
        $filter = str_replace(":filter", "'{$search[":filter"]}'", implode(" OR ", array_map(
            function ($x) {
                return ($x["db"]) . " like :filter";
            },
            $columns
        )));
        /*-- filter --*/

        /*-- order --*/
        $order = "ORDER BY {$columns[$request["order"][0]["column"]]["db"]} {$request["order"][0]["dir"]}";
        /*-- order --*/

        /*-- limit --*/
        $limit = self::limit($request, "sqlsrv");
        /*-- limit --*/

        /*-- query --*/
        $result = AutomaticForm::getDataSql($table, "{$condition} AND ({$filter}) {$order} {$limit}", $showColumn, [
            "checkTableExists" => false
        ]);
        /*-- query --*/

        /*-- data --*/
        foreach ($result as $dKey => $dValue) foreach ($columns as $cValue) {
            $string = (isset($cValue["as"]) ? $dValue[$cValue["as"]] : $dValue[explode(".", $cValue["db"])[$config["isJoin"] ? 1 : 0]]);
            if ($cValue["formatter"] ?? false) $data[$dKey][] = self::formatter(
                $string,
                $cValue["formatter"],
                [
                    $string,
                    $dValue
                ]
            );
            else $data[$dKey][] = $string;
        }
        /*-- data --*/

        /*-- count --*/
        $recordsTotal = AutomaticForm::getDataSql($table, "1 = 1", "count(*) total", [
            "checkTableExists" => false
        ])[0]["total"] ?? 0;
        $recordsFiltered = AutomaticForm::getDataSql($table, $filter, "count(*) total", [
            "checkTableExists" => false
        ])[0]["total"] ?? 0;
        /*-- count --*/

        /*-- return --*/
        return [
            "draw" => $request["draw"],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];
        /*-- return --*/
    }

    static function limit($r, $g)
    {
        return [
            "mysql" => "LIMIT {$r["start"]}, {$r["length"]}",
            "sqlsrv" => "OFFSET {$r["start"]} ROWS FETCH NEXT {$r["length"]} ROWS ONLY"
        ][$g] ?? false;
    }

    static function formatter(String $oldString, Mixed $newString, array $data = [])
    {
        return [
            "string" => !is_object($newString) ? str_replace("@this", $oldString, $newString) : false,
            "object" => is_object($newString) ? $newString(...$data) : false
        ][gettype($newString)] ?? $oldString;
    }
}
