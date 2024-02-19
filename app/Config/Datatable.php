<?php

/**
 * This class handles server-side processing for DataTables.
 * It extends the ProcessData class to utilize its constructor and destructor for managing database connections.
 *
 */

namespace Config;

use Exception;

class Datatable extends CRUD
{
    /**
     * Retrieves data for DataTables based on the provided request and configuration.
     *
     * @param array       $request Data sent by DataTables
     * @param string|array $table   Table name or array for joins
     * @param array       $columns Columns configuration
     * @param array       $config  Additional settings
     *
     * @return array Associative array containing DataTables data
     */
    public function serverSide(array $request, string|array $table, array $columns, array $config = []): array
    {
        try {
            # Original table name (for potential joins)
            $originalTable = $table;

            # Format the table name or join array
            $table = self::formatTable($table);

            # Condition for the query
            $condition = $config["condition"] ?? "1 = 1";

            # Columns to be selected in the query
            $showColumn = self::formatColumns($columns, $config);

            # Filtering based on user input
            $filter = self::applyFilter($columns, $request);

            # Sorting order of the results
            $order = self::applyOrder($columns, $request, $originalTable);

            # Limit the number of results
            $limit = self::applyLimit($request);

            # Execute the query
            $result = ($this->read)($table, "{$condition} AND ({$filter}) {$order} {$limit}", $showColumn);

            # Format the results for DataTables
            $newData = self::dataStructuring($result, $columns);

            # Total number of records without filtering8
            $resultRecordsTotal = ($this->read)($table, $condition, "count(*) total");
            $recordsTotal = $resultRecordsTotal[0]["total"] ?? null;

            # Total number of records after filtering
            $resultRecordsFiltered = ($this->read)($table, "{$condition} AND ({$filter})", "count(*) total");
            $recordsFiltered = $resultRecordsFiltered[0]["total"] ?? null;

            # Return the DataTables data
            return [
                "draw" => $request["draw"],
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $newData
            ];
        } catch (Exception $th) {
            # Log the error or handle it more gracefully
            return [
                "error" => $th->getMessage(),
                "file" => $th->getFile(),
                "line" => $th->getLine()
            ];
        }
    }

    private function dataStructuring(array $result, array $columns): array
    {
        $response = [];

        foreach ($result as $i => $data) foreach ($columns as $key => $value) {
            # Extract the column value based on alias or database name
            $db = explode(".", $value["db"]);
            $string = (isset($value["as"]) ? ($data[$value["as"]] ?? false) : ($data[$db[1] ?? $db[0]] ?? false));

            # Apply formatter function if provided
            if (!empty($string) || is_numeric($string))
                $response[$i][] = $value["formatter"] ? self::applyFormatter($string, $value["formatter"], [$string, $data, $key]) : $string;

            # Handle NULL values with a default message
            else
                $response[$i][] = $value["failed"] ?? '<b class="text-danger">NULL</b>';
        }
        return $response;
    }

    public function formatTable($t): string
    {
        $type = strtoupper(gettype($t));

        if ($type === "ARRAY")
            return implode(" ", $t);
        else
            return $t;
    }

    public function formatColumns($col, $con): string
    {
        return $con["columns"] ?? implode(", ", array_map(function ($columns) {
            $alias = $columns["as"] ?? false;
            return $columns["db"] . ($alias ? " as {$alias}" : "");
        }, $col));
    }

    public function applyFilter($col, $req): string
    {
        return implode(" OR ", array_map(function ($columns) use ($req) {
            if (!isset($req["search"]["value"])) return "";

            $search = "%{$req["search"]["value"]}%";
            return "{$columns["db"]} LIKE '{$search}'";
        }, $col));
    }

    private function applyOrder(array $col, array $req, string|array $ot): string
    {
        // $type = strtoupper(gettype($ot));

        $column = $col[$req["order"][0]["column"]]["db"];
        $order = $req["order"][0]["dir"];

        // return [
        //     "ARRAY" => "ORDER BY {$ot[0]}.{$column} {$order}",
        //     "STRING" => "ORDER BY {$ot}.{$column} {$order}"
        // ][$type] ?? "ORDER BY {$column} {$order}";

        return "ORDER BY {$column} {$order}";
    }

    private function applyLimit(array $req): string
    {
        return [
            "MYSQL" => "LIMIT {$req["start"]}, {$req["length"]}",
            "SQLITE" => "LIMIT {$req["start"]} OFFSET {$req["length"]}", # I haven't worked much with SQLite, not sure if this is correct :(
            "SQLSRV" => "OFFSET {$req["start"]} ROWS FETCH NEXT {$req["length"]} ROWS ONLY"
        ][$this->conn->getGestor()] ?? "";
    }

    /**
     * @param string $oldString Database value
     * @param mixed $newString New value to convert to
     * @param array $data Column data being processed
     * @return string Returns the new value
     */
    private function applyFormatter(string $oldString, mixed $newString, array $data = []): string
    {
        $type = strtoupper(gettype($newString));

        if ($type === "STRING")
            return str_replace("@this", $oldString, $newString); # Simple string formatting
        elseif ($type === "OBJECT")
            return $newString(...$data); # Function formatting
        else
            return $oldString;
    }
}
