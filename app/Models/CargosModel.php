<?php

namespace Model;

use Config\CRUD;
use Config\Datatable;

class CargosModel extends CRUD
{
    const TABLE_CARGO = "cargos";

    public function __construct()
    {
        parent::__construct();
        if (!$this->tableManager->checkTableExists(self::TABLE_CARGO))
            $this->createTableCargo();
    }

    public function createCargo(array $data): array
    {
        return ($this->create)(self::TABLE_CARGO, $data);
    }

    public function readCargo(?string $condition = null): array
    {
        return ($this->read)(self::TABLE_CARGO, $condition ?: "1 = 1");
    }

    public function updateCargo(array $data, int $id): array
    {
        return ($this->update)(self::TABLE_CARGO, $data, "id = {$id}");
    }

    public function deleteCargo(int $id): mixed
    {
        return ($this->delete)(self::TABLE_CARGO, "id = {$id}");
    }

    private function createTableCargo()
    {
        $this->tableManager->createTable(self::TABLE_CARGO);
        $this->tableManager->createColumn(self::TABLE_CARGO, "[nombre]");
    }

    static function serverSideCargo(array $columns, array $config = []): array
    {
        $datatable = new Datatable;
        return $datatable->serverSide($_REQUEST, self::TABLE_CARGO, $columns, $config);
    }
}
