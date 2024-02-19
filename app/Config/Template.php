<?php

namespace Config;

use System\Config\AppConfig;

class Template
{
    const ROUTE_STYLE_BASE = AppConfig::BASE_ADMIN_LTE_3;

    /**
     * Plantilla para contenido de la pagina
     * 
     * @deprecated
     * @param string $template
     * @param array $styles
     * @param array $scripts
     * 
     * @return string Plantilla con los estilo
     */
    static function content(string $template, array $styles = [], array $scripts = []): string
    {

        if (strpos($template, "@style") !== false)
            str_replace("@style", self::styles($styles), $template);
        if (strpos($template, "@script") !== false)
            str_replace("@script", self::scripts($scripts), $template);

        return $template;
    }

    static function styles(array $styles): string
    {
        $styles = array_map(function ($file) {
            if (strpos($file, "http") === false) {
                $file = self::ROUTE_STYLE_BASE . "/$file";
                $file = trim(str_replace([AppConfig::BASE_FOLDER, AppConfig::BASE_SERVER], "", $file), "/");
                $file = AppConfig::BASE_SERVER . "/" . $file;
            }

            return "<link rel=\"stylesheet\" href=\"{$file}\">";
        }, array_filter(array_unique($styles), fn($file) => self::filterFileExists($file)));

        return implode("\n", $styles);
    }

    static function scripts(array $scripts): string
    {
        return str_replace(
            ["<link", "href=", "rel=\"stylesheet\"", ">"],
            ["<script", "src=", "", "></script>"],
            self::styles($scripts)
        );
    }

    static function loadScriptClass(): string
    {
        $classFiles = glob(AppConfig::BASE_FOLDER . "/assets/class/*.js");

        $classLinks = array_map(
            fn($file) => str_replace(AppConfig::BASE_FOLDER, AppConfig::BASE_SERVER, <<<HTML
            <script src="{$file}" defer></script>
            HTML),
            $classFiles
        );

        return implode("\n", $classLinks);
    }


    protected static function filterFileExists(string $file): bool
    {
        if (strpos($file, "http") !== false)
            return true;

        $file = str_replace(self::ROUTE_STYLE_BASE, "", trim(trim($file, "\\"), "/"));

        return file_exists(self::ROUTE_STYLE_BASE . "/{$file}");
    }
}
