<?php

namespace Controller;

use Exception;

class SeeHistoryReport
{
    static function viewSeeHistoryReport(String $id): String
    {
        try {
            $iden = date("YmdHis");
            $idReport = base64_decode($id);
            $HCom = self::timeline($idReport, 1);
            $HHis = self::timeline($idReport, 2);
            return <<<HTML
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab-{$iden}" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-comment-{$iden}-tab{$iden}" data-toggle="pill"
                                    href="#custom-tabs-one-comment-{$iden}" role="tab" aria-controls="custom-tabs-one-comment-{$iden}"
                                    aria-selected="true">Comentarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-report-{$iden}-tab-{$iden}" data-toggle="pill" href="#custom-tabs-one-report-{$iden}"
                                    role="tab" aria-controls="custom-tabs-one-report-{$iden}" aria-selected="false">Sistema</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tab-{$iden}Content">
                            <div class="tab-pane fade show active" id="custom-tabs-one-comment-{$iden}" role="tabpanel"
                                aria-labelledby="custom-tabs-one-comment-{$iden}-tab{$iden}">
                                {$HCom}
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-one-report-{$iden}" role="tabpanel"
                                aria-labelledby="custom-tabs-one-report-{$iden}-tab-{$iden}">
                                {$HHis}
                            </div>
                        </div>
                    </div>
                </div>
            HTML;
        } catch (Exception $th) {
            return <<<HTML
                <h3>Error</h3>
            HTML;
        }
    }

    static function timeline(Int $id, Int $type): String
    {
        $content = "";
        include FOLDER_SIDE . "/conn.php";

        $Comentarios = ($type == 1 ? $db->executeQuery(<<<SQL
            select A.*, B.icon from HorasExtras_Comentario A
            inner join HorasExtras_TipoComentario B on A.id_tipoComentario = B.id
            where A.id_reporte = '{$id}'
        SQL) : ($type == 2 ? $db->executeQuery(<<<SQL
            select * from HorasExtras_Historial_Reportes
            where A.id_reporte = '{$id}'
        SQL) : []));

        $errorComentario = $db->getError($Comentarios);
        if ($errorComentario) $error[] = $errorComentario;

        if (!$errorComentario && !empty(count($Comentarios))) foreach ($Comentarios as $data) {
            $date = date("d M. Y", strtotime($data["fechaRegistro"]));
            $hour = date("H:i", strtotime($data["fechaRegistro"]));
            $titulo = $data["titulo"] ?? "";
            $cuerpo = $data["cuerpo"] ?? "";
            $icon = $data['icon'] ?? "";
            $content .= <<<HTML
                <div class="time-label">
                    <span class="bg-red">{$date}</span>
                </div>

                <div>
                    <i class="{$icon}"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i>{$hour}</span>
                        <h3 class="timeline-header">{$titulo}</h3>
                        <div class="timeline-body">{$cuerpo}</div>
                    </div>
                </div>
            HTML;
        }
        else {
            $fecha = date("Y-m-d H:i:s");
            $date = date("d M. Y", $fecha);
            $hour = date("H:i", $fecha);
            $titulo = "¯\_(ツ)_/¯";
            $icon = $data['icon'] ?? "";
            $content .= <<<HTML
                <div class="time-label">
                    <span class="bg-red">{$date}</span>
                </div>

                <div>
                    <i class="{$icon}"></i>
                    <div class="timeline-item">
                        <span class="time"><i class="fas fa-clock"></i>{$hour}</span>
                        <h3 class="timeline-header">{$titulo}</h3>
                    </div>
                </div>
            HTML;
        }

        return <<<HTML
            <div class="timeline">
                {$content}
            </div>
        HTML;
    }
}
