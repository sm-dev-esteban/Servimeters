<?php

namespace Config;

use Closure;
use PDO;

class CRUD extends ProcessData
{
    # Propiedades de la clase CRUD
    public array $fullRequest;
    public Closure $create, $read, $update, $delete;
    public Closure $renderColumns;
    public $USEFUL;

    # Constructor de la clase
    public function __construct(?PDO $conn = null)
    {
        # Ejecuta el constructor de "ProcessData"
        parent::__construct(conn: $conn);

        # Funciones random para cualquier cosa.
        $this->USEFUL = new USEFUL;

        # Arreglo con los datos completos de solicitud (POST y FILES)
        $this->fullRequest = [...$_POST, ...$_FILES];

        # Definición de funciones anónimas para operaciones CRUD

        # Crear un nuevo registro en la base de datos
        $this->create = fn (string $table, array $data): array
        => $this->prepare($table, $data)->insert();

        # Leer datos de la base de datos
        $this->read = fn (string $table, string $condition = "1 = 1", ?string $columns = "*", array $prepare = []): mixed
        => $this->conn->executeQuery("SELECT " . ($this->renderColumns)($columns ?: "*") . " FROM {$table} WHERE {$condition}", $prepare);

        # Actualizar datos en la base de datos
        $this->update = fn (string $table, array $data, string $condition): array
        => $this->prepare($table, $data)->update($condition);

        # Eliminar datos de la base de datos
        $this->delete = fn (string $table, string $condition, array $prepare = []): mixed
        => $this->conn->executeQuery("DELETE {$table} WHERE {$condition}", $prepare);

        # Eliminar campos repetidos de una lista de columnas
        $this->renderColumns = fn (string $columns): string
        => implode(",", array_unique(array_map(fn ($col): string => trim($col), explode(",", $columns))));
    }
}
