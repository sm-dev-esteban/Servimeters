<?php

namespace Model;

use Config\CRUD;
use Config\Datatable;
use Config\TableManager;
use Exception;

class SolicitudPersonalModel extends CRUD
{
    const TABLE_SOLICITUD = "solicitudPersonal";
    const TABLE_SOLICITUD_PROCESO = self::TABLE_SOLICITUD . "Proceso";
    const TABLE_SOLICITUD_TIPO_CONTRATO = self::TABLE_SOLICITUD . "TipoContrato";
    const TABLE_SOLICITUD_HORARIO = self::TABLE_SOLICITUD . "Horario";
    const TABLE_SOLICITUD_MOTIVO_REQUISICION = self::TABLE_SOLICITUD . "MotivoRequisicion";
    const TABLE_SOLICITUD_ESTADO = self::TABLE_SOLICITUD . "Estados";
    const TABLE_SOLICITUD_HOJAS_DE_VIDA = self::TABLE_SOLICITUD . "HojasDeVida";

    const SOLICITUD_ESTADO = [
        "PENDIENTE"                 => 1,
        "APROBADO JEFE"             => 2,
        "RECHAZO JEFE"              => 3,
        "RECHAZADO"                 => 4,
        "GESTION TALENTO HUMANO"    => 5,
        "CANCELADO"                 => 6
    ];

    public function __construct()
    {
        parent::__construct();
        if (!$this->tableManager->checkTableExists(self::TABLE_SOLICITUD)) self::createTableSolicitud();
    }

    public function createSolicitud(array $data): array
    {
        return ($this->create)(self::TABLE_SOLICITUD, $data);
    }

    public function readSolicitud(?string $condition = null): array
    {
        return ($this->read)(self::TABLE_SOLICITUD, $condition ?: "1 = 1");
    }

    public function updateSolicitud(array $data, int $id): array
    {
        return ($this->update)(self::TABLE_SOLICITUD, $data, "id = {$id}");
    }

    public function deleteSolicitud(): array
    {
        return ($this->delete)(self::TABLE_SOLICITUD);
    }

    private function createTableSolicitud(): void
    {
        $this->tableManager->createTable(self::TABLE_SOLICITUD);

        # Proceso
        $this->tableManager->createColumn(self::TABLE_SOLICITUD, "[id_proceso]", "int default null");
        if (!$this->tableManager->checkTableExists(self::TABLE_SOLICITUD)) self::createTableSolicitud();
        $this->tableManager->addForeignKey(
            [self::TABLE_SOLICITUD  => self::TABLE_SOLICITUD_PROCESO],
            ["id_proceso"           => "id"]
        );

        self::insertInitialData(self::TABLE_SOLICITUD_PROCESO, [
            ["nombre" => "CARGOS OPERATIVOS"],
            ["nombre" => "CARGOS ADMINISTRATIVOS"],
            ["nombre" => "CARGOS TECNICO - COMERCIALES"],
            ["nombre" => "CARGOS DIRECTIVOS"],
            ["nombre" => "CARGOS GERENCIALES"]
        ]);

        # Tipo Contrato
        $this->tableManager->createColumn(self::TABLE_SOLICITUD, "[id_tipo_contrato]", "int default null");
        if (!$this->tableManager->checkTableExists(self::TABLE_SOLICITUD)) self::createTableSolicitud();
        $this->tableManager->addForeignKey(
            [self::TABLE_SOLICITUD  => self::TABLE_SOLICITUD_TIPO_CONTRATO],
            ["id_tipo_contrato"    => "id"]
        );

        self::insertInitialData(self::TABLE_SOLICITUD_TIPO_CONTRATO, [
            ["nombre" => "INDEFINIDO"],
            ["nombre" => "OBRA LABOR"],
            ["nombre" => "FIJO"],
            ["nombre" => "FREELANCE"]
        ]);

        # Horario
        $this->tableManager->createColumn(self::TABLE_SOLICITUD, "[id_horario]", "int default null");
        if (!$this->tableManager->checkTableExists(self::TABLE_SOLICITUD)) self::createTableSolicitud();
        $this->tableManager->addForeignKey(
            [self::TABLE_SOLICITUD  => self::TABLE_SOLICITUD_HORARIO],
            ["id_horario"           => "id"]
        );

        self::insertInitialData(self::TABLE_SOLICITUD_HORARIO, [
            ["nombre" => "OFICINA"],
            ["nombre" => "TURNOS"],
            ["nombre" => "INSPECTORES"]
        ]);

        # Motivo de requisición
        $this->tableManager->createColumn(self::TABLE_SOLICITUD, "[id_motivo_requisicion]", "int default null");
        if (!$this->tableManager->checkTableExists(self::TABLE_SOLICITUD)) self::createTableSolicitud();
        $this->tableManager->addForeignKey(
            [self::TABLE_SOLICITUD  => self::TABLE_SOLICITUD_MOTIVO_REQUISICION],
            ["id_motivo_requisicion"           => "id"]
        );

        self::insertInitialData(self::TABLE_SOLICITUD_MOTIVO_REQUISICION, [
            ["nombre" => "OTRO"],
            ["nombre" => "RETIRO / RENUNCIA EMPLEADO"],
            ["nombre" => "REEMPLAZO POR MATERNIDAD / INCAPACIDAD"],
            ["nombre" => "NUEVO CARGO"],
            ["nombre" => "NUEVO CUPO NÓMINA"]
        ]);

        # Estado
        $this->tableManager->createColumn(self::TABLE_SOLICITUD, "[id_estado]", "int default 1");
        if (!$this->tableManager->checkTableExists(self::TABLE_SOLICITUD)) self::createTableSolicitud();
        $this->tableManager->addForeignKey(
            [self::TABLE_SOLICITUD  => self::TABLE_SOLICITUD_ESTADO],
            ["id_estado"            => "id"]
        );


        foreach (self::SOLICITUD_ESTADO as $name => $id) $initialData[] = ["nombre" => $name];

        self::insertInitialData(self::TABLE_SOLICITUD_ESTADO, $initialData);

        $this->tableManager->createTable(self::TABLE_SOLICITUD_HOJAS_DE_VIDA);
        $this->tableManager->createColumn(self::TABLE_SOLICITUD_HOJAS_DE_VIDA, "[id_solicitud]", "INT DEFAULT NULL");
        $this->tableManager->addForeignKey(
            tables: [self::TABLE_SOLICITUD_HOJAS_DE_VIDA => self::TABLE_SOLICITUD],
            columns: ["id_solicitud" => "id"]
        );
    }

    static function serverSideSolicitud(array $columns, array $config = []): array
    {
        $datatable = new Datatable;

        $const = fn ($name) => [
            "TABLE_SOLICITUD" => self::TABLE_SOLICITUD,
            "TABLE_SOLICITUD_PROCESO" => self::TABLE_SOLICITUD_PROCESO,
            "TABLE_SOLICITUD_ESTADO" => self::TABLE_SOLICITUD_ESTADO,
        ][$name];

        $table = [];

        $table[] = "{$const("TABLE_SOLICITUD")} SP";
        $table[] = "inner join {$const("TABLE_SOLICITUD_PROCESO")} SPP on SP.id_proceso = SPP.id";
        $table[] = "inner join {$const("TABLE_SOLICITUD_ESTADO")} SPE on SP.id_estado = SPE.id";

        return $datatable->serverSide($_REQUEST, $table, $columns, $config);
    }

    /**
     * Inserta datos y aparte crea la tabla junto con las columnas recibidas
     * 
     * @param string $table
     * @param array $initialData
     * 
     */
    protected function insertInitialData(string $table, array $initialData = []): void
    {
        try {
            foreach ($initialData as $data) $this->prepare($table, ["data" => $data])->insert();
        } catch (Exception $th) {
            throw new Exception("Ocurrio un error creando la tabla {$table}: {$th->getMessage()}");
        }
    }
}
