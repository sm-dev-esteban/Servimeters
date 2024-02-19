<?php

namespace Config;

use Closure;
use PDO;

class CRUD extends ProcessData
{
    public Closure $create, $read, $update, $delete;
    private Closure $renderColumns;

    public function __construct(?PDO $conn = null)
    {
        # Ejecuta el constructor de "ProcessData"
        parent::__construct($conn);

        # CRUD
        $this->create = fn (string $table, array $data): array => $this->prepare($table, $data)->insert();
        $this->read = fn (string $table, string $condition = "1 = 1", string $columns = "*", array $prepare = []): mixed => $this->conn->executeQuery("SELECT " . ($this->renderColumns)($columns) . " FROM {$table} WHERE {$condition}", $prepare);
        $this->update = fn (string $table, array $data, string $condition): array => $this->prepare($table, $data)->update($condition);
        $this->delete = fn (string $table, string $condition): mixed => "coming soon ¯\_(ツ)_/¯";

        # Elimina campos repetidos
        $this->renderColumns = fn (string $columns) => implode(",", array_unique(array_map(fn ($col) => trim($col), explode(",", $columns))));
    }
}
