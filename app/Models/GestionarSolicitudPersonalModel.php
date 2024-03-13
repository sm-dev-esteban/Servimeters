<?php

namespace Model;

use Controller\SolicitudPersonal;
use Exception;

class GestionarSolicitudPersonalModel extends SolicitudPersonal
{
    public function approve(array $ids): bool
    {
        try {
            $result = ($this->update)(self::TABLE_SOLICITUD, ["data" => [
                "id_estado" => self::SOLICITUD_ESTADO["APROBADO JEFE"]
            ]], self::implodeIDS(separator: " OR ", array: $ids));

            ["rowCount" => $rowCount] = $result;

            return is_numeric($rowCount) && !empty($rowCount);
        } catch (Exception $th) {
            throw new Exception("Error Processing Request");
        }
    }

    public function decline(array $ids): bool
    {
        try {
            $result = ($this->update)(self::TABLE_SOLICITUD, ["data" => [
                "id_estado" => self::SOLICITUD_ESTADO["RECHAZO JEFE"]
            ]], self::implodeIDS(separator: " OR ", array: $ids));

            ["rowCount" => $rowCount] = $result;

            return is_numeric($rowCount) && !empty($rowCount);
        } catch (Exception $th) {
            throw new Exception("Error Processing Request");
        }
    }

    public function cancel(array $ids): bool
    {
        try {
            $result = ($this->update)(self::TABLE_SOLICITUD, ["data" => [
                "id_estado" => self::SOLICITUD_ESTADO["CANCELADO"]
            ]], self::implodeIDS(separator: " OR ", array: $ids));

            ["rowCount" => $rowCount] = $result;

            return is_numeric($rowCount) && !empty($rowCount);
        } catch (Exception $th) {
            throw new Exception("Error Processing Request");
        }
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
