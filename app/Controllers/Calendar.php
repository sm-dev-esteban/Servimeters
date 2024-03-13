<?php

namespace Controller;

use Exception;
use Model\CalendarModel;

class Calendar extends CalendarModel
{
    public function getEvents(String $start, String $end, bool $render = false): array
    {
        $data = self::readEvent("(([start] >= :START AND [end] <= :END) OR ([start] BETWEEN :BETWEEN_START AND :BETWEEN_END AND [end] IS NULL AND [allDay] = 1))", [
            ":START" => self::fullDate($start),
            ":END" => self::fullDate($end),
            ":BETWEEN_START" => self::fullDate($start),
            ":BETWEEN_END" => self::fullDate($end)
        ]);

        return $render === true ? self::renderEvents(
            data: $data
        ) : $data;
    }

    private function fullDate($datetime)
    {
        return date("Y-m-d H:i:s", strtotime($datetime));
    }

    public function addEvent(?array $data = null)
    {
        $data = $data ?: $this->fullRequest;
        if (isset($data["data"]["start"])) $data["data"]["start"] = self::fullDate($data["data"]["start"]);
        if (isset($data["data"]["end"])) $data["data"]["end"] = self::fullDate($data["data"]["end"]);

        return self::createEvent(
            data: $data
        );
    }

    public function modifyEvent(?array $data = null, int $id)
    {
        if (!$id || empty($id)) throw new Exception("id is required");

        $data = $data ?: $this->fullRequest;
        if (isset($data["data"]["start"])) $data["data"]["start"] = self::fullDate($data["data"]["start"]);
        if (isset($data["data"]["end"])) $data["data"]["end"] = self::fullDate($data["data"]["end"]);
        if (!isset($data["data"]["allDay"])) $data["data"]["allDay"] = false;
        return self::updateEvent(
            data: $data,
            id: $id
        );
    }

    private function getEvent($id): array
    {
        return self::readEvent("[id] = :ID", [
            ":ID" => $id
        ]);
    }

    public function showEvent($id): String
    {
        $result = self::getEvent(id: $id);
        $response = [];

        $formatDate = fn ($date, $format): string => date($format, strtotime($date));

        $prop = fn ($bool, $name): string => $bool ? $name : "";


        foreach ($result as $data) $response[] = <<<HTML
            <div class="modal-body pt-1 fs-9" data-title="{$data['title']}">
                <div class="mt-3 border-bottom pb-3">
                    <h5 class="mb-0">Descripción</h5>
                    <p class="mb-0 mt-2">{$data["description"]}</p>
                </div>

                <div class="mt-4">
                    <h5 class="mb-0">Fecha de inicio</h5>
                    <p class="mb-1 mt-2">{$formatDate($data["start"], "l, F d, Y, h:i A")}</p>
                </div>

                <div class="mt-4 {$prop($data['allDay'], 'd-none')}">
                    <h5 class="mb-0">Fecha de Fin</h5>
                    <p class="mb-1 mt-2">{$formatDate($data["end"], "l, F d, Y, h:i A")}</p>
                </div>

                <button type="button" data-event-update="{$data['id']}" data-dismiss="modal" class="btn btn-sm btn-default float-right">
                    <b><i class="fas fa-pencil-alt fs--2 mr-2"></i> Editar</b>
                </button>
            </div>
        HTML;

        return implode("\n", $response);
    }

    public function formEvent(?int $id = null): String
    {
        $mode = !is_null($id) ? "modifyEvent" : "addEvent";
        $result = self::getEvent(id: $id);

        [
            "title" => $title,
            "description" => $description,
            "start" => $start,
            "end" => $end,
            "allDay" => $allDay
        ] = $result[0];

        $prop = fn ($bool, $name = ""): string => $bool ? $name : "";

        return <<<HTML
        <form data-mode="{$mode}" data-id="{$id}">
            <div class="form-group">
                <label>Titulo</label>
                <input value="{$title}" name="data[title]" type="text" placeholder="Titulo" class="form-control">
            </div>
            <div class="form-group">
                <label>Descripción</label>
                <textarea name="data[description]" placeholder="Descripción" class="form-control" required>{$description}</textarea>
            </div>
            <div class="form-group">
                <label>Fecha de inicio</label>
                <input value="{$start}" name="data[start]" type="datetime-local" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Fecha de fin</label>
                <input value="{$end}" name="data[end]" type="datetime-local" class="form-control" {$prop($allDay, "disabled")}>
            </div>
            <div class="form-check">
                <input value="1" {$prop($allDay, "checked")} name="data[allDay]" type="checkbox" class="form-check-input">
                <label class="form-check-label">¿El evento durara todo el día?</label>
            </div>
            <button class="btn btn-success float-right">Guardar</button>
        </form>
        HTML;
    }
}
