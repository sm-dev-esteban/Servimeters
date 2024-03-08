<?php

namespace Model;

use Config\CRUD;

class TimelineModel extends CRUD
{
    const TABLE_TIMELINE = "timeline";

    public function __construct()
    {
        parent::__construct();
        if (!$this->tableManager->checkTableExists(self::TABLE_TIMELINE)) self::createTableTimeline();
    }

    public function createTimelineEvent(array $data): array
    {
        return ($this->create)(self::TABLE_TIMELINE, $data);
    }

    public function readTimelineEvent(?string $condition): array
    {
        return ($this->read)(self::TABLE_TIMELINE, $condition ?: "1 = 1");
    }

    public function updateTimelineEvent(array $data, int $id): array
    {
        return ($this->update)(self::TABLE_TIMELINE, $data, "id = {$id}");
    }

    public function deleteTimelineEvent(): array
    {
        return ($this->delete)(self::TABLE_TIMELINE);
    }

    private function createTableTimeline(): void
    {
        $this->tableManager->createTable(self::TABLE_TIMELINE);

        # Titulo y description
        $this->tableManager->createColumn(self::TABLE_TIMELINE, "[titulo]");
        $this->tableManager->createColumn(self::TABLE_TIMELINE, "[descripcion]");
        $this->tableManager->createColumn(self::TABLE_TIMELINE, "[pie_de_pagina]");

        # Clases para el icono por defecto un check ✔
        $this->tableManager->createColumn(self::TABLE_TIMELINE, "[icon_class]", "text default \"fas fa-check bg-primary\"");

        # Identificador: Como le quiero dar un uso en general para seguimiento o lo que sea este campo sera la clave para hacer las consultas
        $this->tableManager->createColumn(self::TABLE_TIMELINE, "[identificador]", "text default null");
    }
}
