<?php

namespace Controller;

use Error;
use Exception;
use Model\HorasExtrasModel;
use System\Config\AppConfig;

class HorasExtras extends HorasExtrasModel
{
    public function registrarReporte(array $data): bool
    {
        $result = self::createReport(
            data: $data
        );

        $id = $result["lastInsertId"] ?? null;

        self::timelineReport(
            id: $id,
            titulo: "<a href=\"#\">System</a> Registro inicial.",
            descripcion: "Reporte generado el {$this->date("d/m/Y")} a las {$this->date("H:i")}.",
            icon_class: "fas fa-user-clock bg-info"
        );

        return is_numeric($id);
    }

    public function actualizarReporte(array $data, int $id): bool
    {
        $result = self::updateReport(
            data: $data,
            id: $id
        );

        $count = $result["rowCount"] ?? null;

        self::timelineReport(
            id: $id,
            titulo: "<a href=\"#\">System</a> Registro inicial.",
            descripcion: "Reporte actualizado el {$this->date("d/m/Y")} a las {$this->date("H:i")}.",
            icon_class: "fas fa-user-edit bg-info"
        );

        return !empty($count);
    }

    public function getReport(?string $condition = null, ?string $columns = null): array
    {
        return self::readReport(
            condition: $condition,
            columns: $columns
        );
    }

    public function getHours(int $id_report, ?string $columns = null): array
    {
        return self::readHours(
            condition: "id_reporteHE = {$id_report}",
            columns: $columns
        );
    }

    public function showTimelineReport(int $id): ?string
    {
        return $this->timeline->showTimeline(self::TABLE_REPORT . ":{$id}");
    }

    public function showReport(int $id): ?string
    {
        try {
            $reportResult = self::getReport(condition: "RHE.id = {$id} order by id asc");

            if (!$reportResult) return null;

            $res = "";
            $COMPANY = AppConfig::COMPANY;

            $addressInfo = fn (array $info) => implode("<br>", $info);

            $thead = [
                "Fecha",
                "Actividad",
                "Permisos Descuentos",
                "Extras Diurn Ordinaria",
                "Extras Noct Ordinaria",
                "Extras Diurn Fest Domin",
                "Extras Noct Fest Domin",
                "Recargo Nocturno",
                "Recargo Festivo Diurno",
                "Recargo Festivo Noctur",
                "Recargo Ord Fest Noct"
            ];

            $showInTag = fn (array $array, string $tag = "th") => implode("\n", array_map(fn ($str) => "<{$tag}>{$str}</{$tag}>", $array));

            foreach ($reportResult as $data) {
                $showData = fn ($name): mixed => $data[$name] ?? null;
                $hoursResult = self::getHours(
                    id_report: $showData("id"),
                    columns: "fecha, novedad, Descuento, Ext_Diu_Ord, Ext_Noc_Ord, Ext_Diu_Fes, Ext_Noc_Fes, Rec_Noc, Rec_Fes_Diu, Rec_Fes_Noc, Rec_Ord_Fes_Noc"
                );

                $showTbodyInfo = "";

                foreach ($hoursResult as $dataHours) $showTbodyInfo .= "<tr>{$showInTag(array_values($dataHours))}</tr>";

                # Columna 1
                $col1 = $addressInfo([
                    "<strong>{$showData("reportador_por")}</strong>",
                    "Email: {$showData("correoEmpleado")}",
                    "CC: {$showData("CC")}"
                ]);

                # Columna 2
                $col2 = $addressInfo([
                    "Proyecto Asociado: {$showData("proyecto")}",
                    "Centro De Costo: {$showData("ceco")}",
                    "Clase: {$showData("clase")}"
                ]);

                # Columna 3
                $col3 = $addressInfo([
                    "<b>Reporte #{$showData("id")}</b>",
                    "<b>Fecha Y Hora De registro:</b> {$this->date("d/m/Y H:i A",$showData("fechaRegistro"))}"
                ]);

                $res .= <<<HTML
                <div class="invoice">
                    <!-- Enterprise -->
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                <img src="{$this->imageProcessor::correctImageURL($COMPANY['LOGO_HORIZONTAL'])}" alt="{$COMPANY['NAME']}" class="brand-image" style="width: 150px">
                                <!-- <img src="{$this->imageProcessor::correctImageURL($COMPANY['LOGO'])}" alt="{$COMPANY['NAME']}" class="brand-image img-circle" style="width: 40px"> {$COMPANY["NAME"]} -->
                                <small class="float-right">Fecha: {$this->date("d/m/Y")}</small>
                            </h4>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <address>{$col1}</address>
                        </div>
                        <div class="col-sm-4 invoice-col">
                            <address>{$col2}</address>
                        </div>
                        <div class="col-sm-4 invoice-col">{$col3}</div>
                    </div>
                    <!-- Table -->
                    <div class="row">
                        <div class="col-12 table-reponsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        {$showInTag($thead)}
                                    </tr>
                                </thead>
                                <tbody>{$showTbodyInfo}</tbody>
                                <tfoot>
                                    <tr></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6"></div>
                        <div class="col-6">
                            <p class="lead">Total Reportado</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>Descuentos:</th>
                                        <td>{$showData("Suma_Total_Descuentos")}</td>
                                    </tr>
                                    <tr>
                                        <th>Extras:</th>
                                        <td>{$showData("Suma_Total_Extras")}</td>
                                    </tr>
                                    <tr>
                                        <th>Recargos:</th>
                                        <td>{$showData("Suma_Total_Recargos")}</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td>{$showData("Suma_Total_Horas")}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                HTML;
            }

            return $res;
        } catch (Exception | Error $th) {
            throw new Exception("Ocurrió un error al mostrar la información: {$th->getMessage()}");
        }
    }

    static function sspReport(array $columns, array $config = []): array
    {
        return self::serverSideReport(
            columns: $columns,
            config: $config
        );
    }

    protected function date($format = "Y-m-d H:i:s", ?string $datetime = null): string
    {
        return date($format, strtotime($datetime ?? date("YmdHis")));
    }
}
