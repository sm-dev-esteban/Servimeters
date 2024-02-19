<?php

namespace Controller;

use Model\CentroCostoModel;

class CentroCosto extends CentroCostoModel
{

    public function addCeco(array $data): array
    {
        return $this->createCeco(
            data: $data
        );
    }

    public function editCeco(array $data, int $id): array
    {
        return $this->updateCeco(
            data: $data,
            id: $id
        );
    }

    public function getCeco(?string $condition = null): array
    {
        return $this->readCeco(
            condition: $condition
        );
    }

    static function sspCeco(array $columns, array $config = [])
    {
        return self::serverSideCeco(
            columns: $columns,
            config: $config
        );
    }
}
