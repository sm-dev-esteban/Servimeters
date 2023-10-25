<?php

namespace Model;

use Model\DB;

class ProcessDataStatic
{
    /**
     * @param String $table Nombre de la tabla
     */
    static function checkTableExists(String $table): Bool
    {
        $db = new DB();
        $db->connect();

        // $query = $db->executeQuery("SELECT * from `{$table}` limit 0");
        $query = $db->executeQuery("SELECT top 0 * from `{$table}`");
        $error = DB::getError($query);

        return $error === false ? true : false;
    }

    /**
     * @param String $table Nombre de la tabla
     */
    static function getNamePrimary(String $table): Bool|String
    {
        $db = new DB();
        $db->connect();
        $gestor = $db->getParams("gestor");

        if (!self::checkTableExists($table))
            return false;


        $arrayQuery = [
            "mysql" => "SHOW columns from `{$table}` where `Key` = 'PRI'",
            "sqlsrv" => "SELECT * FROM sys.columns WHERE OBJECT_ID = OBJECT_ID('{$table}') and is_identity = 1",
            "sqlite" => ""
        ];

        $return = [
            "mysql" => "Field",
            "sqlsrv" => "name",
            "sqlite" => ""
        ];

        $q = $arrayQuery[$gestor] ?? "";
        $r = $return[$gestor] ?? "";

        $query = $db->executeQuery($q);
        $error = DB::getError($query);

        if ($error !== false)
            return false;

        $data = $query[0][$r];
        return $data ?? false;
    }
}
