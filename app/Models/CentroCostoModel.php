<?php

namespace Model;

use Config\Datatable;
use Controller\Clase;

class CentroCostoModel extends Clase
{
    const TABLE_CECO = "CentrosCosto";

    public function __construct()
    {
        parent::__construct();
        if (!$this->tableManager->checkTableExists(self::TABLE_CECO))
            self::createTableCeco();
    }

    public function createCeco(array $data): array
    {
        return ($this->create)(self::TABLE_CECO, $data);
    }

    public function readCeco(?string $condition = null): array
    {
        return ($this->read)(self::TABLE_CECO, $condition ?: "1 = 1");
    }

    public function updateCeco(array $data, int $id): array
    {
        return ($this->update)(self::TABLE_CECO, $data, "id = {$id}");
    }

    public function deleteCeco(int $id): mixed
    {
        return ($this->delete)(self::TABLE_CECO, "id = {$id}");
    }

    private function createTableCeco(): void
    {
        $this->tableManager->createTable(self::TABLE_CECO);
        $this->tableManager->createColumn(self::TABLE_CECO, "nombre");
        $this->tableManager->createColumn(self::TABLE_CECO, "id_clase", "int default null");
        $this->tableManager->addForeignKey([self::TABLE_CECO => self::TABLE_CLASS], ["id_clase" => "id"]);
    }

    static function serverSideCeco(array $columns, array $config = []): array
    {
        $datatable = new Datatable;
        return $datatable->serverSide($_REQUEST, self::TABLE_CECO, $columns, $config);
    }
}
