<?php

namespace Model;

use Config\CRUD;
use Config\Datatable;

class ClaseModel extends CRUD
{
    const TABLE_CLASS = "clase";

    public function __construct()
    {
        parent::__construct();
        if (!$this->tableManager->checkTableExists(self::TABLE_CLASS))
            self::createTableClass();
    }

    public function createClass(array $data): array
    {
        return ($this->create)(self::TABLE_CLASS, $data);
    }

    public function readClass(?string $condition = null): array
    {
        return ($this->read)(self::TABLE_CLASS, $condition ?: "1 = 1");
    }

    public function updateClass(array $data, int $id): array
    {
        return ($this->update)(self::TABLE_CLASS, $data, "id = {$id}");
    }

    public function deleteClass()
    {
        return ($this->delete)();
    }

    private function createTableClass(): void
    {
        $this->tableManager->createTable(self::TABLE_CLASS);
        $this->tableManager->createColumn(self::TABLE_CLASS, "[nombre]");
    }

    static function serverSideClass(array $columns, array $config = []): array
    {
        $datatable = new Datatable;
        return $datatable->serverSide($_REQUEST, self::TABLE_CLASS, $columns, $config);
    }
}
