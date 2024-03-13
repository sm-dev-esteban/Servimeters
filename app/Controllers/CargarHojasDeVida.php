<?php

namespace Controller;

use Config\Bs4;
use Model\CargarHojasDeVidaModel;
use System\Config\AppConfig;

class CargarHojasDeVida extends CargarHojasDeVidaModel
{
    public function upload(?array $data = null): bool
    {
        $data = $data ?: $this->fullRequest;

        ["id_report" => $id_report] = $data["data"];

        $this->OPTIMIZE_IMAGES = true;
        $this->filePath = str_replace("@ID_REPORT", $id_report, $this->filePathHV);

        $result = self::subirHojasDevida(
            data: $data ?: $this->fullRequest
        );

        ["lastInsertId" => $lastInsertId] = $result;

        return is_numeric($lastInsertId);
    }

    public function getFoldersForDate(int $id)
    {
        return self::obtenerCarpetasPorFechas(
            id_report: $id
        );
    }

    public function getHVS(int $id)
    {
        return self::obtenerHojasDeVida(
            id_report: $id
        );
    }

    public function showFilesInAccordion(int $id, string $idAccordion, int $indexCollapse = 0): string
    {
        $accordion = [];
        $folders = self::getFoldersForDate($id);

        rsort($folders);

        foreach ($folders as $i => $folder) {
            $files = glob("{$folder}/*");
            $countFiles = count($files);

            # Elimino la carpeta si esta vacia y salto al siguiente ciclo
            if (empty($countFiles)) {
                rmdir($folder);
                continue;
            }

            $folderSplit = explode("/", $folder);
            $folderName = end($folderSplit);

            $content = [];

            foreach ($files as $file) {
                $pathInfo = [...pathinfo($file), ...["size" => filesize($file)]];

                [
                    "size" => $size,
                    "basename" => $basename,
                    "extension" => $extension,
                ] = $pathInfo;

                $bytes = ($this->USEFUL->convertBytes)($size);
                $icon = ($this->USEFUL->viewIconForExtension)($extension);

                $href = str_replace(AppConfig::BASE_FOLDER, AppConfig::BASE_SERVER, $file);

                $content[] = <<<HTML
                <li>
                    <span class="mailbox-attachment-icon">{$icon}</span>
                    <div class="mailbox-attachment-info">
                        <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> {$basename}</a>
                        <span class="mailbox-attachment-size clearfix mt-1">
                            <span>{$bytes}</span>
                            <div class="btn-group float-right">
                                <a href="{$href}" class="btn btn-default btn-sm text-primary" target="_blank"><i class="fas fa-eye"></i></a>
                                <button class="btn btn-default btn-sm text-danger" data-delete-hv><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </span>
                    </div>
                </li>
                HTML;
            }

            $accordion[] = [
                "title" => $folderName,
                "content" => '<ul class="mailbox-attachments clearfix row">' . implode(PHP_EOL, $content) . "</ul>",
                "collapse" => $indexCollapse === $i
            ];
        }

        return Bs4::Accordion("#$idAccordion", $accordion, [
            "card" => [
                "style" => "padding: 0"
            ],
            "card-header" => [
                "style" => "border-bottom: none"
            ]
        ]);
    }

    public function dropHV($filename, $id)
    {
        return self::borrarHojaDeVida(
            filename: $filename,
            id_report: $id
        );
    }
}
