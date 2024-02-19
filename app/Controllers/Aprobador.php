<?php

namespace Controller;

use Model\AprobadorModel;

class Aprobador extends AprobadorModel
{

    public function addApprover(array $data): array
    {
        return $this->createApprover(
            data: $data
        );
    }

    public function editApprover(array $data, int $id): array
    {
        return $this->updateApprover(
            data: $data,
            id: $id
        );
    }

    public function getApprover(?string $condition = null): array
    {
        return $this->readApprover(
            condition: $condition
        );
    }

    public function getApproverType(?string $condition = null): array
    {
        return $this->readApproverType(
            condition: $condition
        );
    }
    public function getApproverManages(?string $condition = null): array
    {
        return $this->readApproverManages(
            condition: $condition
        );
    }

    static function sspApprover(array $columns, array $config = [])
    {
        return self::serverSideApprover(
            columns: $columns,
            config: $config
        );
    }
}
