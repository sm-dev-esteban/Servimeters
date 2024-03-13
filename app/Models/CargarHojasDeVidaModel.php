<?php

namespace Model;

use Controller\GestionarSolicitudPersonal;
use System\Config\AppConfig;

class CargarHojasDeVidaModel extends GestionarSolicitudPersonal
{
    public string $filePathHV = "@TABLE/@ID_REPORT/@DATE";

    public function subirHojasDevida(array $data): array
    {
        return ($this->create)(self::TABLE_SOLICITUD_HOJAS_DE_VIDA, $data);
    }

    public function obtenerCarpetasPorFechas(int $id_report): array|false
    {
        return glob(AppConfig::BASE_FOLDER_FILE . "/" . self::TABLE_SOLICITUD_HOJAS_DE_VIDA . "/{$id_report}/*");
    }

    public function obtenerHojasDeVida(int $id_report)
    {
        return ($this->read)(self::TABLE_SOLICITUD_HOJAS_DE_VIDA, "id_report = :ID_REPORT", "*", [":ID_REPORT" => $id_report]);
    }

    public function borrarHojaDeVida(string $filename, int $id_report)
    {
        $split = explode("/", str_replace("\\", "/", $filename));
        $name = end($split);
        $findHV = ($this->read)(self::TABLE_SOLICITUD_HOJAS_DE_VIDA, "id_report = :ID_REPORT AND adjunto like :ADJUNTO", "*", [
            ":ID_REPORT" => $id_report,
            ":ADJUNTO" => "%{$name}%",
        ]);
        $response = [];
        foreach ($findHV as $data) {
            [
                "id" => $id,
                "fechaRegistro" => $fechaRegistro,
                "id_report" => $id_report,
                "adjunto" => $adjunto,
            ] = $data;

            $fecha = date("Y-m-d", strtotime($fechaRegistro));
            $adjunto = json_decode($adjunto)[0];
            $file = str_replace([
                "@TABLE",
                "@DATE"
            ], [
                self::TABLE_SOLICITUD_HOJAS_DE_VIDA,
                $fecha
            ], $adjunto);
            $response[] = $file;

            # Borro el archivo
            array_map("unlink", glob(AppConfig::BASE_FOLDER_FILE . "/{$file}"));

            # Borro el registro
            ($this->delete)(self::TABLE_SOLICITUD_HOJAS_DE_VIDA, "id = :ID", [":ID" => $id]);
        }

        return $response;
    }
}
