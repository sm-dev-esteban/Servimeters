<?php

namespace Model;

use ReflectionMethod;
use Model\DB;

/**
 * @author Esteban Serna Palacios 😉😜
 * @version 1.7
 */

class ProcessData extends ProcessDataStatic
{
    private $data, $file, $db, $conn, $action, $allData, $idOper;

    private $querys;

    private $config;
    private $executeConfig;

    private $prepare;

    /**
     * @param Array $allData Recibe todos los datos a procesar en dos arreglos [data] - datos / [file] - archivos DEFAULT ARRAY EMPTY
     * @param String $table Nombre de la tabla
     * @param String $action "INSERT" / "UPDATE" DEFAULT "INSERT"
     * @param Array|String|Int $whereUpdate Valores del where solo para "UPDATE"
     */
    public function __construct(
        array $allData = [
            "data" => [],
            "file" => []
        ],
        private String $table,
        String $action = "INSERT",
        private mixed $whereUpdate = false
    ) {
        $this->prepare = [];

        $this->db = new DB();
        $this->conn = $this->db->connect(true);

        $this->idOper = false;

        $this->data = $allData["data"] ?? [];
        $this->file = $allData["file"] ?? [];

        $this->allData = Array_merge($this->data, $this->file);

        $this->action = strtoupper($action);

        if ($this->action == "UPDATE") if (is_Array($this->whereUpdate)) $this->whereUpdate = implode(", ", Array_map(
            function ($k, $v) {
                if (!empty($k) && !is_Array($v)) return "`" . str_replace("@primary", self::getNamePrimary($this->table), $k) . "` = '{$v}'";
                else return "1 = 2";
            },
            Array_keys($this->whereUpdate),
            Array_values($this->whereUpdate)
        ));
        else $this->whereUpdate = "`" . self::getNamePrimary($this->table) . "` = '{$this->whereUpdate}'";

        // baseQuerys
        $this->querys["INSERT"] = "INTO `{$this->table}` (``) VALUES ('')";
        $this->querys["UPDATE"] = "`{$this->table}` SET `` = '' WHERE {$this->whereUpdate}";
        // baseQuerys
    }

    /**
     * @param Mixed $name Nombre del objeto
     * @return Mixed Cualquier objeto inicializado
     */
    public function getParam($name)
    {
        return $this->$name ?? false;
    }

    /**
     * @param Array $config Configuración de ejecución.
     * 1. $config["auto_create"]: crea la tabla y las columnas en la DB. default true
     * 2. $config["checkEmptyValues"]: valida si los campos estan vacios default false
     * 3. $config["separator"]: separador para arreglos default "|/|"
     * @return Array 
     */
    public function execute(array $config = []): array
    {
        // config
        $this->executeConfig = array_merge([
            "auto_create" => true,
            "checkEmptyValues" => false,
            "separator" => "|/|"
        ], $config);
        // config

        // error
        if (empty($this->table) || !in_Array($this->action, ["INSERT", "UPDATE"])) return ["error" => "error params"]; // check params
        if (empty(count($this->data)) && empty(count($this->file))) return ["error" => "empty data"];
        // error

        // createTable
        if ($this->executeConfig["auto_create"] === true) $this->db->createTable($this->table);
        // createTable

        // query
        $rf = new ReflectionMethod(__CLASS__, __FUNCTION__);
        $reflec = [];
        foreach ($rf->getParameters() as $p) $reflec[] =  "$$p->name";
        $q = self::process(...$reflec);
        // query

        // return - execute
        $query = $this->db->executeQuery($q, $this->prepare);
        $error = DB::getError($query);
        $check = $error === false ? true : false;
        $retur = $this->action == "INSERT" ? $this->conn->lastInsertId() : false;

        $this->idOper = $retur;

        return [
            "status" => $check,
            "id" => $this->idOper,
            "query" => $q,
            "error" => $error
        ];
        // return - execute
    }

    private function process()
    {
        if (!empty(count($this->data))) self::processData(...func_get_args());
        if (!empty(count($this->file["name"] ?? []))) self::processFile(...func_get_args());
        self::unnecessary();
        return "{$this->action} {$this->querys[$this->action]}";
    }

    private function unnecessary()
    {
        $this->querys["INSERT"] = str_replace("?=>", "", str_replace(", ``", "", str_replace(", ''", "", $this->querys["INSERT"])));
        $this->querys["UPDATE"] = str_replace("?=>", "", str_replace(", `` = ''", "", $this->querys["UPDATE"]));
        if ($this->db->getParams("gestor") === "SQLSRV") $this->querys = str_replace("`", "", $this->querys);
    }

    private function processData()
    {
        foreach ($this->data as $key => $value) {
            if ($this->executeConfig["auto_create"] === true) $this->db->createColumn($this->table, $key);

            if (is_Array($value)) $value = implode($this->executeConfig["separator"], Array_filter($value, function ($x) {
                return !empty($x);
            }));

            if ($this->executeConfig["checkEmptyValues"] ? !empty($value) : true) {
                $this->prepare[":{$key}"] = $value;

                if ($this->action == "INSERT") $this->querys["INSERT"] = str_replace("``", "`?=>{$key}`, ``", str_replace("''", "?=>:{$key}, ''", $this->querys["INSERT"]));
                else if ($this->action == "UPDATE") $this->querys["UPDATE"] = str_replace("`` = ''", "`?=>{$key}` = ?=>:{$key}, `` = ''", $this->querys["UPDATE"]);
            }
        }
    }

    private function processFile()
    {
        define("FOLDER_FILE", FOLDER_SIDE . "/files/{$this->table}/");
        define("SERVER_FILE", SERVER_SIDE . "/files/{$this->table}/");
        foreach ($this->file["name"] as $key => $value) {
            if ($this->executeConfig["auto_create"] === true) $this->db->createColumn($this->table, $key);

            if (!file_exists(FOLDER_FILE)) @mkdir(FOLDER_FILE, 0777, true);

            $date = date("Y-m-d H-i-s");

            if (is_Array($value)) {
                foreach ($this->file["name"][$key] as $keyM => $valueM) if (!empty($this->file["tmp_name"][$key][$keyM])) {
                    $value[$keyM] = FOLDER_FILE . "{$date}_{$this->file["name"][$key][$keyM]}";
                    move_uploaded_file($this->file["tmp_name"][$key][$keyM], $value[$keyM]);
                    $value[$keyM] = str_replace(FOLDER_FILE, SERVER_FILE, $value[$keyM]);
                }
                $value = implode($this->executeConfig["separator"], $value);
            } else if (!empty($this->file["tmp_name"][$key])) {
                $value = FOLDER_FILE . "{$date}_{$value}"; // le cambiamos el nombre al archivo con toda la ruta donde se va a cargar 
                move_uploaded_file($this->file["tmp_name"][$key], "{$value}"); // subimos el archivo
                $value = str_replace(FOLDER_FILE, SERVER_FILE, $value);
            }

            if ($this->executeConfig["checkEmptyValues"] ? !empty($value) : true) {
                $this->prepare[":{$key}"] = $value;

                if ($this->action == "INSERT") $this->querys["INSERT"] = str_replace("``", "`?=>{$key}`, ``", str_replace("''", "?=>:{$key}, ''", $this->querys["INSERT"]));
                else if ($this->action == "UPDATE") $this->querys["UPDATE"] = str_replace("`` = ''", "`?=>{$key}` = ?=>:{$key}, `` = ''", $this->querys["UPDATE"]);
            }
        }
    }
}
