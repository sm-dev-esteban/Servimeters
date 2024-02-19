<?php

namespace Model;

use Config\Datatable;
use Controller\HorasExtras;

class GestionarHorasExtrasModel extends HorasExtras
{
    static function serverSideManageOvertime(array $columns, array $config = []): array
    {
        $datatable = new Datatable;

        $table = [];

        return $datatable->serverSide($_REQUEST, self::TABLE_REPORT, $columns, $config);
    }
}
