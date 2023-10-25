<?php // https://datatables.net/manual/server-side

namespace Model;

use Model\DB;

/**
 * @author Es mio 👿
 */

class DataTable
{
    /**
     * @param Array $request datos enviados por el datatable
     * @param String|Array $table $table Nombre de la tabla
     * @param Array $columns arreglo con las columnas que se van a mostrar en la tabla
     * @param Array $config Configuración adicional.
     * 1. $config["condition"]: Condiciones extra para la consulta Ejemplo "1 = 1"
     * 2. $config["columns"]: Columnas a retornar util para formmater function "Por defecto solo se puede acceder a las columnas con las que esta filtrando"
     * @param Bool $DB Conexion a la base de datos 
     */
    static function serverSide(
        array $request,
        String|array $table,
        array $columns,
        array $config = [],
        Bool $DB = false
    ): array {
        $DB = ($DB === false ? new DB() : $DB);
        $DB->connect();

        $data = [];
        $dataError = [];
        $error = "";

        $search = [":filter" => "%{$request["search"]["value"]}%"];
        $condition = $config["condition"] ?? "1 = 1";

        /*-- table --*/
        $mainTable = $table;
        $table = self::table($table);
        /*-- columns --*/
        $showColumn = self::columns($columns, $config);
        /*-- filter --*/
        $filter = self::filter($columns, $search);
        /*-- order --*/
        $order = self::order($request, $columns, $mainTable);
        /*-- limit --*/
        $limit = self::limit($request, $DB->getParams("gestor"));
        /*-- query --*/
        $query = <<<SQL
            SELECT $showColumn FROM $table WHERE $condition AND ($filter) $order $limit
        SQL;

        /*-- query --*/
        // $result = $DB->executeQuery($query, $search);
        $result = $DB->executeQuery($query);
        $dataError[] = DB::getError($result);

        /*-- data --*/
        foreach ($result as $dKey => $dValue) foreach ($columns as $cKey => $cValue) {
            $x = explode(".", $cValue["db"]);
            $string = (isset($cValue["as"]) ? ($dValue[$cValue["as"]] ?? false) : ($dValue[$x[1] ?? $x[0]] ?? false));

            if ($string)
                if ($cValue["formatter"] ?? false)
                    $data[$dKey][] = self::formatter(
                        $string,
                        $cValue["formatter"],
                        [
                            $string, // valor de la base de datos
                            $dValue, // datos de la fila
                            $cKey // identificador
                        ]
                    );
                else $data[$dKey][] = $string;
            else $data[$dKey][] = $cValue["failed"] ?? <<<HTML
                <b class="text-danger">NULL</b>
            HTML;
        }

        /*-- count --*/
        # (I don't speak english 😥) sin wo filter
        $recordsTotal = $DB->executeQuery(<<<SQL
            SELECT count(*) total FROM {$table} WHERE {$condition}
        SQL)[0]["total"] ?? 0;

        # with filter
        // $recordsFiltered = $DB->executeQuery(<<<SQL
        // SELECT count(*) total FROM $table WHERE {$condition} AND ({$filter})
        // SQL, $search)[0]["total"] ?? 0;
        $recordsFiltered = $DB->executeQuery(<<<SQL
            SELECT count(*) total FROM {$table} WHERE {$condition} AND ({$filter})
        SQL)[0]["total"] ?? 0;

        // $dataError[] = DB::getError($recordsTotal);
        // $dataError[] = DB::getError($recordsFiltered);

        /*-- error --*/
        $error = implode(" - ", array_filter($dataError, function ($x) {
            return ($x);
        }));

        /*-- return --*/
        return [
            "draw" => $request["draw"],
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
            "error" => empty($error) ? false : $error,
            "query" => $query
        ];
    }
    static function table($t): String
    {
        $type = strtoupper(gettype($t));

        if ($type === "ARRAY") return implode(" ", $t);
        else return $t;
    }
    /**
     * @param Array $c columns
     * @param Array $co config
     * @return String columns
     */
    static function columns($c, $co): String
    {
        return $co["columns"] ?? implode(", ", array_map(
            function ($x) {
                return $x["db"] . (isset($x["as"]) ? " as {$x["as"]}" : "");
            },
            $c
        ));
    }

    /**
     * @param Array $c columns
     * @param Array $f filter
     * @return String filter
     */
    static function filter($c, $f): String
    {
        # no me dejo con las consultas preparadas porque no coincide el numero de columnas de los parametros tonces ni modo
        // return implode(" OR ", array_map(function ($x) {
        //     return ($x["db"] ?? false ? "{$x["db"]} LIKE :filter" : "");
        // }, $c));

        return implode(" OR ", array_map(function ($x) use ($f) {
            return ($x["db"] ?? false ? "{$x["db"]} LIKE '{$f[":filter"]}'" : "");
        }, $c));
    }

    /**
     * @param Array $r request data
     * @param Array $c columns
     * @param String $t table
     * @return String order
     */
    static function order($r, $c, $mt): String
    {
        $column = $c[$r["order"][0]["column"]]["db"];
        $order = $r["order"][0]["dir"];

        if (explode(".", $column)[1] ?? false) return "ORDER BY {$column} {$order}";

        return [
            "ARRAY" => ($mt[0] ?? false ? "ORDER BY {$mt[0]}.{$column} {$order}" : ""),
            "STRING" => "ORDER BY " . (explode(" ", $mt)[0] ?? "") . ".{$column} {$order}" // Esta validación muy posiblemente falle :c
        ][strtoupper(gettype($mt))] ?? "ORDER BY {$column} {$order}";
    }

    /**
     * @param Array $r request data
     * @param String $g gestor DB
     * @return String limit by gestor
     */
    static function limit($r, $g): String
    {
        return [
            "MYSQL" => "LIMIT {$r["start"]}, {$r["length"]}",
            "SQLSRV" => "OFFSET {$r["start"]} ROWS FETCH NEXT {$r["length"]} ROWS ONLY"
        ][strtoupper($g)] ?? false;
    }

    /**
     * @param String $oldString Valor de la base de datos
     * @param Mixed $newString Nuevo valor al que lo quieran convertir
     * @param Array $data Datos de la columna que este recorriendo
     * @return String retorna el nuevo valor
     */
    static function formatter(String $oldString, Mixed $newString, array $data = []): String
    {
        $type = strtoupper(gettype($newString));

        if ($type === "STRING") return str_replace("@this", $oldString, $newString);
        elseif ($type === "OBJECT") return $newString(...$data);
        else return $oldString;
    }
}
