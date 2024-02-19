<?php

namespace Controller;

use Error;
use Exception;
use Model\TimelineModel;

class Timeline extends TimelineModel
{
    public function register(array $data): array
    {
        return self::createTimelineEvent(
            data: $data
        );
    }

    public function showTimeline($identificador): ?string
    {
        try {
            $timelineResult = self::readTimelineEvent("identificador = '{$identificador}' order by id asc");

            if (!$timelineResult) return null;

            $res = "";
            $timeLabel = [];

            foreach ($timelineResult as $data) {
                $fechaRegistro = $data["fechaRegistro"];
                $icon_class = $data["icon_class"] ?: "fas fa-question";
                $titulo = $data["titulo"] ?? "";
                $descripcion = $data["descripcion"] ?? "";
                $pie_de_pagina = $data["pie_de_pagina"] ?? "";

                [$date, $hour] = explode(" ", $fechaRegistro);

                if (!in_array($date, $timeLabel)) {
                    $timeLabel[] = $date;
                    $timeLabelDate = date("d M. Y", strtotime($date));

                    $res .= <<<HTML
                        <div class="time-label">
                            <span class="bg-red">{$timeLabelDate}</span>
                        </div>
                    HTML;
                }

                $timeLabelHour = date("H:i", strtotime($hour));
                $res .= <<<HTML
                    <div>
                        <i class="{$icon_class}"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fas fa-clock"></i> {$timeLabelHour}</span>
                            <h3 class="timeline-header">{$titulo}</h3>
                            <div class="timeline-body">{$descripcion}</div>
                            <div class="timeline-footer">{$pie_de_pagina}</div>
                        </div>
                    </div>
                HTML;
            }

            return <<<HTML
                <div class="timeline">
                    {$res}
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            HTML;
        } catch (Exception | Error $th) {
            throw new Exception("Ocurrió un error al mostrar la información: {$th->getMessage()}");
        }
    }
}
