<?php

namespace Controller;

use Model\GestionarHorasExtrasModel;

class GestionarHorasExtras extends GestionarHorasExtrasModel
{
    static function sspManageOvertime(array $columns, array $config = []): array
    {
        return self::serverSideManageOvertime(
            columns: $columns,
            config: $config
        );
    }
}
