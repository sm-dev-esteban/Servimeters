<?php

namespace Controller;

use Model\ClaseModel;

class Clase extends ClaseModel
{

    public function addClass(array $data): array
    {
        return $this->createClass(
            data: $data
        );
    }

    public function editClass(array $data, int $id): array
    {
        return $this->updateClass(
            data: $data,
            id: $id
        );
    }

    public function getClass(?string $condition = null): array
    {
        return $this->readClass(
            condition: $condition
        );
    }

    static function sspClass(array $columns, array $config = [])
    {
        return self::serverSideClass(
            columns: $columns,
            config: $config
        );
    }
}
