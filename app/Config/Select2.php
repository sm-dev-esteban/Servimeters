<?php

namespace Config;

use Exception;

class Select2 extends Datatable
{
    public function remoteData(array $request, string $table, string $id, string $text, array $config = []): array
    {
        try {
            # Original table name (for potential joins)
            $originalTable = $table;

            # Format the table name or join array
            $table = parent::formatTable($table);

            # Condition for the query
            $condition = $config["condition"] ?? "1 = 1";

            $columns = [
                ["db" => $id, "as" => "id"],
                ["db" => $text, "as" => "text"],
            ];

            $request["search"]["value"] = $request["q"] ?? $request["term"] ?? "";

            # Columns to be selected in the query
            $showColumn = self::formatColumns($columns, $config);

            # Filtering based on user input
            $filter = self::applyFilter($columns, $request);

            $result = ($this->read)($table, "{$condition} AND ({$filter})", $showColumn);

            return [
                "results" => $result
            ];
        } catch (Exception $th) {
            return [
                "error" => $th->getMessage()
            ];
        }
    }
}
