<?php

namespace Controller;

use Exception;

class SeeApplicationReport extends SeeHoursReport
{
    static function viewApplicationReport(String $id): String
    {
        try {
            include FOLDER_SIDE . "/conn.php";
            $idReport = base64_decode($id);

            $solicitudP = $db->executeQuery(<<<SQL
                select * from solicitudPersonal where id = '{$idReport}'
            SQL);
            $errorSP = $db->getError($solicitudP);
            if ($errorSP) throw new Exception($errorSP, 1);

            $show = "";

            $columns = [
                [
                    "db" => "id",
                    "col" => 4,
                    "label" => "N° Requisición"
                ], [
                    "db" => "fechaRegistro", "formatter" => function ($d) {
                        return date("Y-m-d", strtotime($d));
                    },
                    "col" => 4,
                    "label" => "Fecha de creación"
                ], [
                    "db" => "id_proceso", "formatter" => function ($d) use ($db) {
                        $x = $db->executeQuery(<<<SQL
                            select nombre from requisicion_proceso where id = {$d}
                        SQL);
                        return $x[0]["nombre"] ?? ":c";
                    },
                    "col" => 4,
                    "label" => "Proceso"
                ], [
                    "db" => "nombreCargo",
                    "col" => 4,
                    "label" => "Nombre del cargo"
                ], [
                    "db" => "ciudad",
                    "col" => 4,
                    "label" => "Ciudad"
                ], [
                    "db" => "descripcionActividades",
                    "col" => 12,
                    "label" => "Descripción de actividades, principales"
                ], [
                    "db" => "codigo",
                    "col" => 4,
                    "label" => "Código"
                ], [
                    "db" => "contrato", "formatter" => function ($d) use ($db) {
                        $x = $db->executeQuery(<<<SQL
                            select nombre from requisicion_contrato where id = {$d}
                        SQL);
                        return $x[0]["nombre"] ?? ":c";
                    },
                    "col" => 4,
                    "label" => "Tipo de contrato"
                ], [
                    "db" => "horario", "formatter" => function ($d) use ($db) {
                        $x = $db->executeQuery(<<<SQL
                            select nombre from requisicion_horario where id = {$d}
                        SQL);
                        return $x[0]["nombre"] ?? ":c";
                    },
                    "col" => 4,
                    "label" => "Horario"
                ], [
                    "db" => "meses",
                    "col" => 4,
                    "label" => "Meses"
                ], [
                    "db" => "sueldo", "formatter" => function ($d) {
                        return number_format($d, 2, ",", ".");
                    },
                    "col" => 4,
                    "label" => "Sueldo"
                ], [
                    "db" =>
                    "auxilioExtralegal", "formatter" => function ($d) {
                        return number_format($d, 2, ",", ".");
                    },
                    "col" => 4,
                    "label" => "Auxilio Extralegal"
                ], [
                    "db" => "motivoRequisicion", "formatter" => function ($d) use ($db) {
                        $x = $db->executeQuery(<<<SQL
                            select nombre from requisicion_motivo where id = {$d}
                        SQL);
                        return $x[0]["nombre"] ?? ":c";
                    },
                    "col" => 4,
                    "label" => "Motivo de Requisición"
                ], [
                    "db" => "fechaContratacion", "formatter" => function ($d) {
                        if (date("Ymd", strtotime($d)) > date("Ymd"))
                            return <<<HTML
                                <b class="text-danger">{$d}</b>
                            HTML;
                        else return $d;
                    },
                    "col" => 4,
                    "label" => "Fecha a contratar"
                ], [
                    "db" => "reemplazaA",
                    "col" => 4,
                    "label" => "Reemplaza a"
                ], [
                    "db" => "recursos",
                    "col" => 4,
                    "label" => "Recursos necesarios para el cargo"
                ], [
                    "db" => "otroMotivoRequisicion",
                    "col" => 4,
                    "label" => "Otro motivo de requisición"
                ], [
                    "db" => "radicadoPor",
                    "col" => 4,
                    "label" => "Radicado por"
                ]
            ];

            # no se me ocurrio algo que no fuera tan manual asi que ni modos :c
            foreach ($solicitudP as $data) {
                $show .= '<div class="row">';
                foreach ($columns as $dataFormat) if ($data[$dataFormat["db"]] ?? false) {
                    $content = (isset($dataFormat["formatter"]) ? $dataFormat["formatter"]($data[$dataFormat["db"]]) : $data[$dataFormat["db"]]);
                    if (!empty($content)) $show .= <<<HTML
                        <div class="col-12 col-xl-{$dataFormat['col']}">
                            <p><b>{$dataFormat["label"]}: </b>{$content}</p>
                        </div>
                    HTML;
                }
                $show .= '</div>';
            }

            $arrayFiles = self::getFileReport($idReport);
            $files = "";

            foreach ($arrayFiles as $dataFiles) {
                $viewIcon = self::viewIcon($dataFiles);
                $sizes = self::convertBytes($dataFiles["size"] ?? 0);
                $href = $dataFiles["dirname"] . "/" . $dataFiles["basename"];
                $files .= <<<HTML
                    <li>
                        {$viewIcon}
                        <div class="mailbox-attachment-info">
                            <p style="color: white;mix-blend-mode: difference" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> {$dataFiles["basename"]}</p>
                            <span class="mailbox-attachment-size clearfix mt-1">
                                <span>{$sizes}</span>
                                <a href="{$href}" class="btn btn-default btn-sm float-right m-1" download><i class="fas fa-cloud-download-alt"></i></a>
                                <a href="{$href}" class="btn btn-default btn-sm float-right m-1" target="_blank"><i class="fas fa-eye"></i></a>
                            </span>
                        </div>
                    </li>
                HTML;
            }
            return <<<HTML
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        {$show}
                        <hr>
                        <ul class="mailbox-attachments d-flex align-items-stretch clearfix table-responsive">
                            {$files}
                        </ul>
                        
                </div>
            HTML;
        } catch (Exception $th) {
            return <<<HTML
                <h3>Failed Get Report (┬┬﹏┬┬)</h3>
            HTML;
        }
    }

    static function getFileReport($id)
    {
        include FOLDER_SIDE . "/conn.php";

        $hv = $db->executeQuery(trim(
            <<<SQL
                select * from requisicion_hojas_de_vida where id_requisicion = :ID
            SQL
        ), [
            ":ID" => $id
        ]);

        $res = [];

        foreach ($hv as $data)
            $res[] = $data["hojasDeVida"] ?? "";
        return self::getFiles(implode("|/|", $res));
    }
}
