<?php

require_once(dirname(__DIR__) . "/controller/views/session.controller.php");

class LoadConfig extends sessionController
{
    private static $path;
    static function getConfig()
    {
        self::$path = dirname(__DIR__) . "/config/config.json";

        if (file_exists(self::$path)) {
            $json_data = file_get_contents(self::$path);
            return json_decode($json_data);
        } else {
            // return 'No hay archivo';
            return false;
        }
    }
}
