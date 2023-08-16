<?php // https://datatables.net/manual/server-side

include_once(dirname(__DIR__) . "/controller/automaticForm.php");

class DataTable
{
    /**
     * @param array $request datos enviados por el datatable
     * @param String $table Nombre de la tabla
     * @param array $columns arreglo con las columnas que se van a mostrar en la tabla
     * @param array $config Configuración adicional.
     * 1. $config["condition"]: Condiciones para la consulta
     * 2. $config["columns"]: Este campo afecta las columnas a las cuales puede acceder el "formatter". Por defecto solo se podrá acceder a los valores que esté enviando
     * 3. deprecated $config["isJoin"]: No se si es una buena idea, pero voy a manejarlo desde el codigo (⌐■_■)
     */
    static function serverSide(
        array $request,
        String $table,
        array $columns,
        array $config = []
    ): array {

        $config = array_merge([
            "condition" => false,
            "columns" => false
        ], $config);

        $data = [];

        $search = [":filter" => "%{$request["search"]["value"]}%"];

        $condition = $config["condition"] !== false ? $config["condition"] : "1 = 1";

        /*-- general --*/
        $showColumn = self::columns($request, $columns, $config);
        $filter = self::filter($request, $columns, $search[":filter"]);
        $order = self::order($request, $columns, $table);
        $limit = self::limit($request, $columns, "sqlsrv");
        /*-- general --*/

        /*-- query --*/
        $result = AutomaticForm::getDataSql($table, "{$condition} AND ({$filter}) {$order} {$limit}", $showColumn, [
            "checkTableExists" => false
        ]);
        /*-- query --*/

        /*-- data --*/
        foreach ($result as $dKey => $dValue) foreach ($columns as $cValue) {
            $db = explode(".", $cValue["db"]);
            $x = $dValue[$cValue["as"] ?? $db[1] ?? $db[0]] ?? "undefined";
            if ($cValue["formatter"] ?? false) $data[$dKey][] = self::formatter(
                $x,
                $cValue["formatter"],
                [
                    $x, $dValue
                ]
            );
            else $data[$dKey][] = $x;
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

    static function columns($r, $c, $co)
    {
        return $co["columns"] !== false ? $co["columns"] : implode(", ", array_map(
            function ($x) {
                if ($x["db"] ?? false) return $x["db"] . " " . ($x["as"] ?? "");
                else return "";
            },
            $c
        ));
    }

    static function filter($r, $c, $s)
    {
        return str_replace(":filter", "'{$s}'", implode(" OR ", array_map(
            function ($x) {
                if ($x["db"] ?? false) return ($x["db"]) . " like :filter";
                else return "";
            },
            $c
        )));
    }

    static function order($r, $c, $t)
    {
        return "ORDER BY " . (strpos($t, " join ") === false ? "{$t}." : "") . "{$c[$r["order"][0]["column"]]["db"]} {$r["order"][0]["dir"]}";
    }

    static function limit($r, $c, $g)
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
