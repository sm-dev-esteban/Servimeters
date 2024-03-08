<?php

namespace Controller;

use Model\SolicitudPersonalModel;

class SolicitudPersonal extends SolicitudPersonalModel
{
    public function registrarSolicitud(array $data): array
    {
        return self::createSolicitud(
            data: $data
        );
    }

    static function sspSolicitud(array $columns, array $config = [])
    {
        return self::serverSideSolicitud(
            columns: $columns,
            config: $config
        );
    }
}
