<?php

namespace Controller;

use Model\CargosModel;

class Cargos extends CargosModel
{
    public function addCargo($data): array
    {
        return $this->createCargo(
            data: $data
        );
    }

    public function editCargo(array $data, int $id)
    {
        return $this->updateCargo(
            data: $data,
            id: $id
        );
    }

    public function getCargo(?string $condition = null)
    {
        return $this->readCargo(
            condition: $condition
        );
    }

    static function sspCargo(array $columns, array $config = [])
    {
        return self::serverSideCargo(
            columns: $columns,
            config: $config
        );
    }
}
