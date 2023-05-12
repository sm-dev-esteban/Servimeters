<?php

class LoadConfig
{
    private static $path;
    public static function getConfig()
    {
        self::$path = realpath($_SERVER["DOCUMENT_ROOT"] . '/' . explode("/", $_SERVER['REQUEST_URI'])[1] . '/config/config.json');

        if (file_exists(self::$path)) {
            $json_data = file_get_contents(self::$path);
            return json_decode($json_data);
        } else {
            // return 'No hay archivo';
            return false;
        }
    }
}
