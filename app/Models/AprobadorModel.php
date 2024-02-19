<?php

namespace Model;

use Config\Datatable;
use Controller\CentroCosto;

class AprobadorModel extends CentroCosto
{
    const TABLE_APPROVER = "Aprobador";
    const TABLE_APPROVER_TYPE = self::TABLE_APPROVER . "_tipo";
    const TABLE_APPROVER_MANAGES = self::TABLE_APPROVER . "_gestion";

    public function __construct()
    {
        parent::__construct();
        if (!$this->tableManager->checkTableExists(self::TABLE_APPROVER)) self::createTableApprover();
    }

    public function createApprover(array $data): array
    {
        return ($this->create)(self::TABLE_APPROVER, $data);
    }

    public function readApprover(?string $condition = null): array
    {
        return ($this->read)(self::TABLE_APPROVER, $condition ?: "1 = 1");
    }

    public function readApproverType(?string $condition = null): array
    {
        return ($this->read)(self::TABLE_APPROVER_TYPE, $condition ?: "1 = 1");
    }

    public function readApproverManages(?string $condition = null): array
    {
        return ($this->read)(self::TABLE_APPROVER_MANAGES, $condition ?: "1 = 1");
    }

    public function updateApprover(array $data, int $id): array
    {
        return ($this->update)(self::TABLE_APPROVER, $data, "id = {$id}");
    }

    public function deleteApprover(int $id): mixed
    {
        return ($this->delete)(self::TABLE_APPROVER, "id = {$id}");
    }

    private function createTableApprover(): void
    {
        $this->tableManager->createTable(self::TABLE_APPROVER);
        $this->tableManager->createColumn(self::TABLE_APPROVER, "nombre");
        $this->tableManager->createColumn(self::TABLE_APPROVER, "email");
        # permisos
        $this->tableManager->createColumn(self::TABLE_APPROVER, "admin", "BIT DEFAULT 'FALSE'");
        $this->tableManager->createColumn(self::TABLE_APPROVER, "apruebaSolicitudPersonal", "BIT DEFAULT 'FALSE'");
        $this->tableManager->createColumn(self::TABLE_APPROVER, "apruebaSolicitudPermisos", "BIT DEFAULT 'FALSE'");
        # tipo
        $this->tableManager->createColumn(self::TABLE_APPROVER, "id_tipo", "int default 1");
        $this->createTableType()->tableManager->addForeignKey(
            tables: [self::TABLE_APPROVER => self::TABLE_APPROVER_TYPE],
            columns: ["id_tipo" => "id"]
        );
        # gestion
        $this->tableManager->createColumn(self::TABLE_APPROVER, "id_gestiona", "int default 1");
        $this->createTablegestion()->tableManager->addForeignKey(
            tables: [self::TABLE_APPROVER => self::TABLE_APPROVER_MANAGES],
            columns: ["id_gestiona" => "id"]
        );

        $initialData = [
            [
                "nombre" => "N/A",
                "email" => "N/A"
            ]
        ];

        self::insertInitialData(self::TABLE_APPROVER, $initialData);
    }

    private function createTableType(): self
    {
        $initialData = [
            ["nombre" => "N/A"],
            ["nombre" => "JEFE"],
            ["nombre" => "GERENTE"]
        ];

        return self::insertInitialData(self::TABLE_APPROVER_TYPE, $initialData);
    }
    private function createTablegestion(): self
    {
        $initialData = [
            ["nombre" => "N/A"],
            ["nombre" => "RH"],
            ["nombre" => "CONTABLE"]
        ];

        return self::insertInitialData(self::TABLE_APPROVER_MANAGES, $initialData);
    }

    static function serverSideApprover(array $columns, array $config): array
    {
        $const = fn (string $name): string => [
            "TABLE_APPROVER" => self::TABLE_APPROVER,
            "TABLE_APPROVER_TYPE" => self::TABLE_APPROVER_TYPE,
            "TABLE_APPROVER_MANAGES" => self::TABLE_APPROVER_MANAGES,
        ][$name];

        $datatable = new Datatable;

        return $datatable->serverSide($_REQUEST, [
            "{$const("TABLE_APPROVER")} APPROVER",
            "inner join {$const("TABLE_APPROVER_TYPE")} TYPE on APPROVER.id_tipo = TYPE.id",
            "inner join {$const("TABLE_APPROVER_MANAGES")} MANAGES on APPROVER.id_gestiona = MANAGES.id",
        ], $columns, $config);
    }

    protected function insertInitialData($table, $initialData): self
    {
        foreach ($initialData as $data) @$this->prepare($table, ["data" => $data])->insert();
        return $this;
    }
}
