<?php

namespace Controller;

use Model\GestionarHorasExtrasModel;

class GestionarHorasExtras extends GestionarHorasExtrasModel
{
    public function aprobar(array $data = []): bool
    {
        return self::aprobarReporte(
            dataRequest: $data ?: $this->fullRequest
        );
    }

    public function rechazar(array $data = []): bool
    {
        return self::rechazarReporte(
            dataRequest: $data ?: $this->fullRequest
        );
    }

    static function sspManageOvertime(array $columns, array $config = []): array
    {
        return self::serverSideManageOvertime(
            columns: $columns,
            config: $config
        );
    }
}
