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
}
