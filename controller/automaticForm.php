<?php

namespace Controller;

use Model\ProcessData;

class AutomaticForm
{
    // private $af;
    // public function __construct()
    // {
    //     $this->af = new ProcessData([], false);
    // }
    /**
     * @param Array $data datos a procesar
     * @param String $table nombre de la tabla
     * @return Array resultado
     */
    static function insert($table, $data)
    {
        $process = new ProcessData($data, $table, "INSERT", false);
        return $process->execute();
    }

    /**
     * @param Array $data datos a procesar
     * @param String $table nombre de la tabla
     * @param Array|String $where condicion de la consulta
     * @return Array resultado
     */
    static function update($table, $data, $where)
    {
        $process = new ProcessData($data, $table, "UPDATE", $where);
        return $process->execute(
            [
                "checkEmptyValues" => true
            ]
        );
    }
}
