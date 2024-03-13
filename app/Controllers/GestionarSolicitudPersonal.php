<?php

namespace Controller;

use Model\GestionarSolicitudPersonalModel;

class GestionarSolicitudPersonal extends GestionarSolicitudPersonalModel
{
    public function aprobar(?array $ids = null): bool
    {
        return self::approve(
            ids: $ids ?: $this->fullRequest["ids"]
        );
    }

    public function rechazar(?array $ids = null): bool
    {
        return self::decline(
            ids: $ids ?: $this->fullRequest["ids"]
        );
    }

    public function cancelar(?array $ids = null): bool
    {
        return self::cancel(
            ids: $ids ?: $this->fullRequest["ids"]
        );
    }
}
