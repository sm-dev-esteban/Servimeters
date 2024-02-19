<?php

namespace Model;

use Config\Datatable;
use Controller\Aprobador;
use Controller\Timeline;
use Exception;

class HorasExtrasModel extends Aprobador
{

    const TABLE_REPORT = "ReportesHE";
    const TABLE_REPORT_STATUS = self::TABLE_REPORT . "_estado";
    const TABLE_REPORT_LIMIT_HOURS = self::TABLE_REPORT . "_config";
    const TABLE_HOURS = "HorasExtra";

    public $timeline = null;

    public function __construct()
    {
        parent::__construct();

        $this->timeline = new Timeline;
        if (!$this->tableManager->checkTableExists(self::TABLE_REPORT)) self::createTableReport();
    }

    public function createReport(array $data): array
    {
        try {
            $result = $this->prepare(self::TABLE_REPORT, $data)->insert();
            $id = $result["lastInsertId"] ?? null;

            $HorasExtra = [];
            if (isset($data["HorasExtra"])) foreach ($data["HorasExtra"] as $key => $Hours) {
                $i = 0;
                if (is_array($Hours)) foreach ($Hours as $value) {
                    $HorasExtra[$i]["id_reporteHE"] = $id;
                    $HorasExtra[$i][$key] = $value;
                    $i++;
                }
            }

            for ($i = 0; $i < count($HorasExtra); $i++) $this->prepare(self::TABLE_HOURS, ["data" => $HorasExtra[$i]])->insert();

            return $result;
        } catch (Exception $th) {
            throw new Exception("Ocurrio un error al realizar el reporte: {$th->getMessage()}");
        }
    }

    public function readReport(?string $condition = null, ?string $columns = null)
    {
        return ($this->read)(
            self::TABLE_REPORT . " RHE inner join " . self::TABLE_CECO . " CC on RHE.id_ceco = CC.id inner join " . self::TABLE_CLASS . " C on CC.id_clase = C.id",
            $condition ?: "1 = 1",
            $columns ?: "RHE.*, CC.nombre ceco, C.nombre clase"
        );
    }

    public function readHours(?string $condition = null, ?string $columns = null)
    {
        return ($this->read)(
            self::TABLE_HOURS,
            $condition ?: "1 = 1",
            $columns ?: "*"
        );
    }

    public function updateReport()
    {
    }

    public function deleteReport()
    {
    }

    public function timelineReport($titulo = "", $descripcion = "", $icon_class = "", $id): self
    {
        $this->timeline->register(
            data: [
                "data" => [
                    "titulo" => $titulo,
                    "descripcion" => $descripcion,
                    "icon_class" => $icon_class,
                    "identificador" => self::TABLE_REPORT . ":$id",
                ]
            ]
        );
        return $this;
    }

    private function createTableReport(): void
    {
        $this->tableManager->createTable(self::TABLE_REPORT);
        $this->tableManager->createColumn(self::TABLE_REPORT, "CC", "Int default null");
        $this->tableManager->createColumn(self::TABLE_REPORT, "cargo");
        $this->tableManager->createColumn(self::TABLE_REPORT, "mesReportado");
        $this->tableManager->createColumn(self::TABLE_REPORT, "correoEmpleado");
        $this->tableManager->createColumn(self::TABLE_REPORT, "proyecto");
        $this->tableManager->createColumn(self::TABLE_REPORT, "enviaAdjuntos", "Boolean default false");
        $this->tableManager->createColumn(self::TABLE_REPORT, "checkAprobador");
        $this->tableManager->createColumn(self::TABLE_REPORT, "fecha_inicio", "Date default null");
        $this->tableManager->createColumn(self::TABLE_REPORT, "fecha_fin", "Date default null");
        $this->tableManager->createColumn(self::TABLE_REPORT, "reportador_por");
        $this->tableManager->createColumn(self::TABLE_REPORT, "codigos");
        # Total
        $this->tableManager->createColumn(self::TABLE_REPORT, "Total_Descuento", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Total_Ext_Diu_Ord", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Total_Ext_Noc_Ord", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Total_Ext_Diu_Fes", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Total_Ext_Noc_Fes", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Total_Rec_Noc", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Total_Rec_Fes_Diu", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Total_Rec_Fes_Noc", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Total_Rec_Ord_Fes_Noc", "Int default 0");
        # Suma Total
        $this->tableManager->createColumn(self::TABLE_REPORT, "Suma_Total_Descuentos", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Suma_Total_Extras", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Suma_Total_Recargos", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_REPORT, "Suma_Total_Horas", "Int default 0");
        # Ceco
        $this->tableManager->createColumn(self::TABLE_REPORT, "id_ceco", "Int default null");
        $this->tableManager->addForeignKey([self::TABLE_REPORT => self::TABLE_CECO], ["id_ceco" => "id"]);
        # Estado
        $this->tableManager->createColumn(self::TABLE_REPORT, "id_estado", "Int default 1");
        if (!$this->tableManager->checkTableExists(self::TABLE_REPORT_STATUS)) $this->createTableReportStatus();
        $this->tableManager->addForeignKey(
            [self::TABLE_REPORT => self::TABLE_REPORT_STATUS],
            ["id_estado"        => "id"]
        );
        # Aprobador
        $this->tableManager->createColumn(self::TABLE_REPORT, "id_aprobador", "Int default 1");
        $this->tableManager->addForeignKey([self::TABLE_REPORT => self::TABLE_APPROVER], ["id_aprobador" => "id"]);
        # Horas
        if ($this->tableManager->checkTableExists(self::TABLE_HOURS)) $this->createTableReportHours();
        # Config
        if (!$this->tableManager->checkTableExists(self::TABLE_HOURS))  $this->createTableReportHours();
    }

    private function createTableReportStatus(): self
    {
        $initialData = [
            ["nombre" => "EDICION"],
            ["nombre" => "APROBADO"],
            ["nombre" => "RECHAZO"],
            ["nombre" => "APROBACION_JEFE"],
            ["nombre" => "APROBACION_GERENTE"],
            ["nombre" => "RECHAZO_GERENTE"],
            ["nombre" => "APROBACION_RH"],
            ["nombre" => "RECHAZO_RH"],
            ["nombre" => "APROBACION_CONTABLE"],
            ["nombre" => "RECHAZO_CONTABLE"]
        ];

        return self::insertInitialData(self::TABLE_REPORT_STATUS, $initialData);
    }

    private function createTableReportHours(): void
    {
        $this->tableManager->createTable(self::TABLE_HOURS);
        $this->tableManager->createColumn(self::TABLE_HOURS, "fecha", "Date default null");
        $this->tableManager->createColumn(self::TABLE_HOURS, "novedad");
        # Descuentos
        $this->tableManager->createColumn(self::TABLE_HOURS, "Descuento", "Int default 0");
        # Extras
        $this->tableManager->createColumn(self::TABLE_HOURS, "Ext_Diu_Ord", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_HOURS, "Ext_Noc_Ord", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_HOURS, "Ext_Diu_Fes", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_HOURS, "Ext_Noc_Fes", "Int default 0");
        # Recargos
        $this->tableManager->createColumn(self::TABLE_HOURS, "Rec_Noc", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_HOURS, "Rec_Fes_Diu", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_HOURS, "Rec_Fes_Noc", "Int default 0");
        $this->tableManager->createColumn(self::TABLE_HOURS, "Rec_Ord_Fes_Noc", "Int default 0");
    }

    static function serverSideReport(array $columns, array $config = []): array
    {
        $datatable = new Datatable();

        return $datatable->serverSide($_REQUEST, self::TABLE_REPORT, $columns, $config);
    }

    protected function insertInitialData($table, $initialData): self
    {
        foreach ($initialData as $data) $this->prepare($table, ["data" => $data])->insert();
        return $this;
    }
}
