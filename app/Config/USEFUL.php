<?php

namespace Config;

use Closure;

/**
 * Clase para cosas random que pueden ser útiles. ヾ(•ω•`)o
 */

class USEFUL
{
    public Closure $options;
    public Closure $thead;
    public Closure $convertBytes;
    public Closure $viewIconForExtension;
    public Closure $lineBreak;

    const MEDIDAS = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    const FACTOR = 1024;

    public function __construct()
    {
        /**
         * Muestra opciones con el resultado de una consulta
         * 
         * @param array $array Resultado de la consulta
         * @param string $value Valor de la opción
         * @param string $text Texto de la opción.
         * @param mixed $selected Preselecciona una de las opciones si coincide con el valor o el texto
         * @return string Devuelve las opciones separadas por salto de línea
         */
        $this->options = fn (array $array, string $value, string $text, mixed $selected = null): string
        => implode("\n", array_map(
            function ($data) use ($value, $text, $selected) {
                $value = $data[$value] ?? "";
                $text = $data[$text] ?? "";
                $selected = $selected == $value || $selected == $text ? "selected" : "";

                return "<option value=\"{$value}\" {$selected}>{$text}</option>";
            },
            $array
        ));

        /**
         * Muestra el encabezado.
         * 
         * @param array $columns Columnas a mostrar
         * @param bool $equalColumns Divide el ancho en partes iguales.
         * @param string $tag Aunque lo cree solo para encabezado de la tabla, lo dejo abierto para cambiar la etiqueta.
         * @return string Devuelve el encabezado separado por salto de línea
         */
        $this->thead = fn (array $columns, bool $equalColumns = true, string $tag = "th"): string
        => implode("\n", array_map(function ($col) use ($columns, $equalColumns, $tag) {
            $count = count($columns);
            $width = 100 / $count;
            $styleWidth = $equalColumns ? "width: {$width}%" : "";

            return Html::createTag(
                tagName: $tag,
                attrs: ["style" => $styleWidth],
                innerHtml: $col
            );
        }, $columns));

        /**
         * Agrega la etiqueta de <br> separando el texto por saltos de linea.
         * "La cree para escribir texto en las alertas, pero siver para cualquier parte del html"
         * 
         * @param string $str
         * @return string texto codificado para html
         */
        $this->lineBreak = fn (string $str): string => htmlspecialchars(implode("<br>", explode(PHP_EOL, $str)));

        /**
         * Conversión de bytes
         * 
         * @param int $bytes
         * @return string Retorna el valor junto con la unidad
         */
        $this->convertBytes = function (int $bytes): string {
            foreach (self::MEDIDAS as $unidad) {
                if ($bytes < self::FACTOR) return round($bytes, 2) . " {$unidad}";
                $bytes /= self::FACTOR;
            }

            return round($bytes, 2) . " " . end(self::MEDIDAS);
        };

        /**
         * Muestra un icono segun la extensión
         * 
         * @param ?string $extension
         * @return string <i />
         */
        $this->viewIconForExtension = function (?string $extension = null): string {
            $icons = [
                # pdf
                'pdf' => 'far fa-file-pdf',
                # word
                'docx' => 'far fa-file-word',
                'docm' => 'far fa-file-word',
                'dotx' => 'far fa-file-word',
                'dotm' => 'far fa-file-word',
                # excel
                'xls' => 'far fa-file-excel',
                'xlsx' => 'far fa-file-excel',
                'xlsm' => 'far fa-file-excel',
                'xltx' => 'far fa-file-excel',
                'xltm' => 'far fa-file-excel',
                'xlsb' => 'far fa-file-excel',
                'xlam' => 'far fa-file-excel',
                # powerpoint
                'pptx' => 'far fa-file-powerpoint',
                'pptm' => 'far fa-file-powerpoint',
                'potx' => 'far fa-file-powerpoint',
                'potm' => 'far fa-file-powerpoint',
                'ppam' => 'far fa-file-powerpoint',
                'ppsx' => 'far fa-file-powerpoint',
                'ppsm' => 'far fa-file-powerpoint',
                'sldx' => 'far fa-file-powerpoint',
                'sldm' => 'far fa-file-powerpoint',
                'thmx' => 'far fa-file-powerpoint',
                # image
                'jpg' => 'far fa-image',
                'png' => 'far fa-image',
                'gif' => 'far fa-image',
                'bmp' => 'far fa-image',
                'svg' => 'far fa-image',
                'webp' => 'far fa-image',
                'ico' => 'far fa-image',
                'tiff' => 'far fa-image',
                'jpeg' => 'far fa-image',
                'apng' => 'far fa-image',
                'svgz' => 'far fa-image'
            ];

            return "<i class=\"{$icons[$extension]}\"></i>" ?? "<i class=\"far fa-file\"></i>";
        };
    }
}
