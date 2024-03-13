<?php

namespace Model;

use Controller\HorasExtras;
use Exception;

class GestionarHorasExtrasModel extends HorasExtras
{
    public function aprobarReporte(array $dataRequest): bool
    {
        try {
            foreach ($dataRequest as $key => $data) {
                $id_aprobador = $data["aprobador"] ?: null;
                $ids = $data["ids"] ?: [];

                switch (strtoupper($key)) {
                    case ':GERENTE':
                        if (!empty($ids)) self::actualizarAprobador(
                            id_aprobador: $id_aprobador ?: "id_aprobador",
                            id_estado: self::HOURS_STATUS["APROBACION_GERENTE"],
                            ids: $ids
                        );
                        break;
                    case ':RH':
                        if (!empty($ids)) self::actualizarAprobador(
                            id_aprobador: $id_aprobador ?: "id_aprobador",
                            id_estado: self::HOURS_STATUS["APROBACION_RH"],
                            ids: $ids
                        );
                        break;
                    case ':CONTABLE':
                        if (!empty($ids)) self::actualizarAprobador(
                            id_aprobador: $id_aprobador ?: "id_aprobador",
                            id_estado: self::HOURS_STATUS["APROBACION_CONTABLE"],
                            ids: $ids
                        );
                        break;
                    case ':APROBADO':
                        if (!empty($ids)) self::actualizarAprobador(
                            id_aprobador: 1,
                            id_estado: self::HOURS_STATUS["APROBADO"],
                            ids: $ids
                        );
                        break;
                        // default:
                        //     return false;
                        //     break;
                }
            }

            return true;
        } catch (Exception $th) {
            throw new Exception("Ocurrió un error aprobando los reportes: {$th->getMessage()}");
        }
    }

    public function rechazarReporte(array $dataRequest): bool
    {
        try {
            foreach ($dataRequest as $key => $data) {
                $id_aprobador = $data["aprobador"] ?: null;
                $ids = $data["ids"] ?: [];

                switch (strtoupper($key)) {
                    case ':EMPLEADO':
                        if (!empty($ids)) self::actualizarAprobador(
                            id_aprobador: 1,
                            id_estado: self::HOURS_STATUS["RECHAZO"],
                            ids: $ids
                        );
                        break;
                    case ':JEFE':
                        if (!empty($ids)) self::actualizarAprobador(
                            id_aprobador: $id_aprobador ?: "id_aprobador",
                            id_estado: self::HOURS_STATUS["RECHAZO_GERENTE"],
                            ids: $ids
                        );
                        break;
                    case ':GERENTE':
                        if (!empty($ids)) self::actualizarAprobador(
                            id_aprobador: $id_aprobador ?: "id_aprobador",
                            id_estado: self::HOURS_STATUS["RECHAZO_RH"],
                            ids: $ids
                        );
                        break;
                    case ':RECHAZO':
                        if (!empty($ids)) self::actualizarAprobador(
                            id_aprobador: 1,
                            id_estado: self::HOURS_STATUS["RECHAZO_CONTABLE"],
                            ids: $ids
                        );
                        break;
                }
            }

            return true;
        } catch (Exception $th) {
            throw new Exception("Ocurrió un error aprobando los reportes: {$th->getMessage()}");
        }
    }

    static function serverSideManageOvertime(array $columns, array $config = []): array
    {
        return self::serverSideReport(
            columns: $columns,
            config: $config
        );
    }

    protected function actualizarAprobador(int $id_aprobador, int $id_estado, array $ids): void
    {
        ($this->update)(self::TABLE_REPORT, [
            "data" => [
                "id_aprobador" => $id_aprobador,
                "id_estado" => $id_estado
            ]
        ], self::implodeIDS(separator: " OR ", array: $ids));
    }

    protected function implodeIDS(array|string $separator = "", array $array): string
    {
        return implode(
            $separator,
            array_map(
                fn ($id) => "id = '{$id}'",
                array_filter(
                    $array,
                    fn ($id) => is_numeric($id) && !empty($id)
                )
            )
        );
    }
}
