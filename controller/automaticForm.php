<?php

use JetBrains\PhpStorm\Internal\returnTypeContract;

require_once $_SERVER["DOCUMENT_ROOT"] . '/' . explode("/", $_SERVER['REQUEST_URI'])[1] . "/config/DB.config.php";

/*
 * - CREATE
 * 27/04/2023
 * Cree la estructura general de todo el codigo
 * - UPDATE
 * 03/05/2023
 * Cambio general en las consultas las hice con mysql y esto esta en sql server y pos bueno no funcionaba
 * Agregue getters para obtener información
 * Corrección de varios errores
 * - TESTING
 * 04/05/2023
 * Pruebas en general y documentar bien todo el codigo
 */

/**
 * @author Esteban Serna Palacios 😉😜
 * @version 1.2.1
 */

class AutomaticForm extends DB
{

    private $data;
    private $file;
    private $db;
    private $conn;
    private $config;
    private $table;
    private $action;
    private $update;
    private $alldata;
    private $idOper;

    /**
     * @param Array $alldata Recibe dos arreglos, el primero [ "data" ] para información en general y el otro es para archivos [ "file" ] -- si algo pararle como parámetro $_FILES.
     * @param String $table Nombre de la tabla si no existe puede ser creada.
     * @param String $action La accion recive uno de dos parametros "INSERT" o "UPDATE"
     * @param Mixed $update Campo únicamente sirve para hacer updates puede recibir un arreglo con el nombre de su llave primaria o también puede recibir solo el valor y el código se encarga de buscar la llave de esa tabla.
     */
    public function __construct(
        array $alldata = [
            "data" => [],
            "file" => []
        ],
        String $table,
        String $action = "INSERT",
        Mixed $update = [
            "@primary" => false
        ]
    ) {
        $this->table = $table;
        $this->action = strtoupper($action);

        if ($this->action == "UPDATE") {
            if (!is_array($update)) { // si no es un arreglo busco a la llave primaria de la tabla y que filtre por el valor que le estoy pasando
                $this->update = [self::getNamePrimary($this->table) => $update];
            } else { // si es un arreglo verguero el que me toco hacer :c

                $check = key(array_filter($update, function ($x) { // busco que el arreglo contenga la palabra @primary para hacerle el cambio de la llave primaria de esa tabla
                    return str_contains($x, "@primary");
                }, ARRAY_FILTER_USE_KEY));

                if (!is_null($check)) {
                    $this->update = [self::getNamePrimary($this->table) => $update[$check]];
                } else {
                    $this->update = $update;
                }
            }
        } else {
            $this->update = ["ident" => false];
        }

        $this->db = new DB();
        $this->conn = $this->db->Conectar();
        $this->config = self::getConfig();

        $this->alldata = $alldata;

        $this->data = (isset($this->alldata["data"]) && !empty(count($this->alldata["data"])) ? $this->alldata["data"] : false);
        $this->file = (isset($this->alldata["file"]) && !empty(count($this->alldata["file"])) ? $this->alldata["file"] : false);

        $this->idOper = 0;
    }

    /**
     * @return Array Devuelve algunos de los parametros definidos en el __construct
     */
    public function getParams(): array
    {
        return [
            "table"     => $this->table,
            "action"    => $this->action,
            "data"      => $this->data,
            "file"      => $this->file,
            "config"    => $this->config
        ];
    }

    /**
     * @return Int Devuelve el ID de la operación
     */
    public function getId(): Int|String
    {
        return $this->idOper;
    }

    /**
     * @return Array Devuelve todos los datos enviados
     */
    public function getAllData(): array
    {
        return $this->alldata;
    }

    /**
     * @return Array Devuelve los datos enviados
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return Array Devuelve los archivos enviados
     */
    public function getFile(): array
    {
        return $this->file;
    }

    /**
     * @return Array Devuelve el arreglo para actualizar los datos
     */
    public function getUpdate(): Mixed
    {
        return $this->update;
    }

    /**
     * @param bool $auto_craete Válida la existencia de los campos y las tablas y si no existen las crea.
     * @param bool $checkEmptyValues Válida que los campos no este vacío, si recibe un dato que este vacío lo omite tanto en la creación como en la ación a realizar.
     * @return array Devuelve un arreglo con el estado y el ID de la operación, ya sea insert o update.
     */
    public function execute(Bool $auto_craete = true, Bool $checkEmptyValues = false): array
    {

        if (empty($this->table | $this->action) || ($this->action == "INSERT" || $this->action == "UPDATE" ? false : true)) {
            return ["error" => "Error params"];
        } else if ($this->action == "UPDATE") {
            $id_u = key($this->update);
            $va_u = $this->update[$id_u];
        }

        if ($auto_craete == true) {
            if (!self::checkTableExists($this->table)) {
                // creamos la tabla en el caso de que no exista
                $this->conn->beginTransaction();

                // creamos los campos si no existen
                $query = $this->conn->prepare("

                    IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = '{$this->table}' and xtype = 'U')
                        CREATE TABLE {$this->table} (
                            id INT IDENTITY(1,1) PRIMARY KEY,
                            fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP
                        )
                    ");
                $query->execute();
                $this->conn->commit();
            }

            $checkAll = [];

            $checkAll = array_merge(
                $this->data <> false && isset($this->data) && is_array($this->data) ? $this->data : [],
                $this->file <> false && isset($this->file["name"]) && is_array($this->file["name"]) ? $this->file["name"] : []
            );

            foreach ($checkAll as $key => $value) {
                if ($checkEmptyValues && !is_array($value) ? !empty($value) : !empty($key)) {
                    $this->conn->beginTransaction();

                    // creamos las columnas si no existen
                    $query = $this->conn->prepare("
                        IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$this->table}' AND COLUMN_NAME = '{$key}')
                            BEGIN
                            ALTER TABLE {$this->table} ADD {$key} VARCHAR(MAX) DEFAULT NULL
                        END
                        ");
                    $query->execute();
                    $this->conn->commit();
                }
            }
        }

        // variables que uso como plantilla
        // Nota: no cambiar por que me onojo >:(

        $insert = "(``) VALUES ('')";
        $update = "`` = ''";

        // data
        if ($this->data <> false && is_array($this->data) && !empty(count($this->data))) {
            foreach ($this->data as $key => $value) {
                if ($checkEmptyValues ? !empty($value) : !empty($key)) {
                    if (is_array($value)) {
                        $value = implode("|/|", array_filter($value, function ($x) {
                            return !empty($x);
                        })); // hago que cada registro quede separado con este valo |/|
                    }
                    if ($this->action == "INSERT") {
                        $insert = str_replace("``", "`?=>{$key}`, ``", str_replace("''", "'?=>{$value}', ''", $insert));
                    } else if ($this->action == "UPDATE") {
                        $update = str_replace("`` = ''", "`?=>{$key}` = '?=>{$value}', `` = ''", $update);
                    }
                }
            }
        }
        // data

        // files
        // Nota: adjuntar comprimidos
        if ($this->file <> false && is_array($this->file["name"]) && !empty(count($this->file["name"]))) {

            define("FOLDER_SITE", "{$this->config->FOLDER_SITE}files/{$this->table}/");
            define("URL_SITE", "{$this->config->URL_SITE}files/{$this->table}/");

            foreach ($this->file["name"] as $key => $value) {
                if ($checkEmptyValues ? !empty($value) : !empty($key)) {

                    if (!file_exists(FOLDER_SITE)) { // creamos la carpeta si no existeF
                        mkdir(FOLDER_SITE, 0777, true);
                    }

                    if (is_array($value)) {
                        foreach ($this->file["name"][$key] as $keyM => $valueM) {
                            if (!empty($this->file["tmp_name"][$key][$keyM])) {
                                $value[$keyM] = FOLDER_SITE . date("YmdHis") . "_{$this->file["name"][$key][$keyM]}";
                                move_uploaded_file($this->file["tmp_name"][$key][$keyM], $value[$keyM]);
                                $value[$keyM] = str_replace(FOLDER_SITE, URL_SITE, $value[$keyM]);
                            }
                        }
                        $value = implode("|/|", $value);
                    } else {
                        if (!empty($this->file["tmp_name"][$key])) {
                            $value = FOLDER_SITE . date("YmdHis") . "_{$value}"; // le cambiamos el nombre al archivo con toda la ruta donde se va a cargar 
                            move_uploaded_file($this->file["tmp_name"][$key], "{$value}"); // subimos el archivo
                            $value = str_replace(FOLDER_SITE, URL_SITE, $value);
                        }
                    }

                    if ($this->action == "INSERT") {
                        $insert = str_replace("``", "`?=>{$key}`, ``", str_replace("''", "'?=>{$value}', ''", $insert));
                    } else if ($this->action == "UPDATE") {
                        $update = str_replace("`` = ''", "`?=>{$key}` = '?=>{$value}', `` = ''", $update);
                    }
                }
            }
        }
        // files

        $insert = str_replace("`", "", str_replace("?=>", "", str_replace(", ``", "", str_replace(", ''", "", $insert))));
        $update = str_replace("`", "", str_replace("?=>", "", str_replace(", `` = ''", "", $update)));

        $q = $this->action == "INSERT" ? "{$this->action} INTO {$this->table} {$insert}" : "{$this->action} {$this->table} SET {$update} WHERE {$id_u} = '{$va_u}'";

        try {
            $this->conn->beginTransaction();
            $query = $this->conn->prepare($q);
            $checkQUery = $query->execute();
            $this->conn->commit();

            $returnID = $this->action == "INSERT" ? $this->conn->lastInsertId() : $va_u;

            $this->idOper = $returnID;

            return ["status" => $checkQUery ? true : false, "id" => $returnID, "query" => $q];
        } catch (PDOException $th) {
            return ["status" => false, "query" => $q, "error" => $th->errorInfo];
        }
    }

    /**
     * @param String $table nombre de la tabla
     * @return String Solo retorna el nombre de la llave primaria de una tabla, algo innecesario pero útil.
     */
    static function getNamePrimary(String $table): String
    { // no he probado esta madre, pero confio en cristo rey :)
        if (!self::checkTableExists($table)) {
            return "Tabla es obligatoria";
        }
        $db = new DB();
        $conn = $db->Conectar();

        $q = "SELECT * FROM sys.columns WHERE OBJECT_ID = OBJECT_ID('{$table}') and is_identity = 1";

        try {
            $query = $conn->prepare($q);

            if (!$query->execute()) {
                return false;
            } else {
                $data = $query->fetch(PDO::FETCH_ASSOC);
                return (isset($data["name"]) && !empty($data["name"]) ? $data["name"] : false);
            }
        } catch (PDOException $th) {
            return $th->errorInfo;
        }
    }

    /**
     * @param String $filter campo a filtrar
     * @param String $column columna a filtrar
     * @param String $return columna que va a devolver
     * @param String $table nombre de la tabla
     * @param Array  $config variable especial para cambiar algunos parametros 
     * @return String devuelve el valor enviado en $return si esta vacia o no se ejecuta la consulta devuelve false
     */
    static function getValueSql($filter, $column, $return, $table, $config = []): String|Int
    {

        $defaultConfig = [
            "like" => false,
            "notResult" => false,
            "checkTableExists" => true
        ];

        $c = array_merge($defaultConfig, is_array($config) ? $config : []);

        if ($c["checkTableExists"] ? !self::checkTableExists($table) : false) {
            return "Tabla es obligatoria";
        }


        $db = new DB();
        $conn = $db->Conectar();
        $primaryKey = self::getNamePrimary($table);

        $q = "SELECT {$return} FROM {$table}
            WHERE {$column} " . ($c["like"] == true ? " like '%{$filter}%' " : " = '{$filter}' ") . "
            ORDER BY {$primaryKey}
            OFFSET 0 ROWS FETCH NEXT 1 ROWS ONLY";

        $q = str_replace("@primary", $primaryKey, $q);

        try {
            $query = $conn->prepare($q);

            if (!$query->execute()) {
                return $c["notResult"];
            } else {
                $data = $query->fetch(PDO::FETCH_ASSOC);
                return (isset($data[$return]) && !empty($data[$return]) ? $data[$return] : $c["notResult"]);
            }
        } catch (PDOException $th) {
            return $th->errorInfo;
        }
    }

    /**
     * @param String $table nombre de la tabla
     * @param String $where condición
     * @param String $return valores a devolver por defecto todos
     */
    static function getDataSql(String $table, String $where = "1 = 1", String $return = "*", $config = []): array
    {
        $defaultConfig = [
            "checkTableExists" => true
        ];

        $c = array_merge($defaultConfig, is_array($config) ? $config : []);

        if ($c["checkTableExists"] ? !self::checkTableExists($table) : false) {
            return ["error" => "Tabla es obligatoria"];
        }

        $db = new DB();
        $conn = $db->Conectar();

        $q = "SELECT {$return} FROM {$table} WHERE {$where}";

        if (strpos($q, "@primary") !== false) {
            $q = str_replace("@primary", self::getNamePrimary($table), $q);
        }

        try {
            $query = $conn->prepare($q);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $th) {
            return ["error" => $th->errorInfo, "query" => $q];
        }
    }

    /**
     * @param String|Int $value valor con el que va a filtrar
     * @param String $column campo con el que va a filtrar
     * @param String|Int|Array $primaryKey valor de la llave primaria ejemplo: id = $primaryKey para el array enviar una arreglo con campo y valor a filtrar ejemplo (["id_micosita" => 1] - id_micosita = 1) cada campo es separado por un AND
     * @param String $table nombre de la tabla
     * @return Array retorna un arreglo con el status y el error en el caso de que suceda
     */
    static function updateValueSql($value, String $column, $primaryKey, String $table): array
    {
        if (!self::checkTableExists($table)) {
            return ["error" => "Tabla es obligatoria"];
        }
        $db = new DB();
        $conn = $db->Conectar();
        // $primaryKey
        if (is_array($primaryKey)) {
            foreach ($primaryKey as $key => $val) {
                $x[] = "$key = '{$val}'";
            }
        }
        $where = is_array($primaryKey)
            ? implode(" AND ", $x)
            : self::getNamePrimary($table) . " = '{$primaryKey}'";

        $q = "UPDATE {$table} set {$column} = '{$value}' where $where";

        try {
            $query = $conn->prepare($q);
            $query->execute();
            return ["status" => true, "query" => $q];
        } catch (PDOException $th) {
            return ["status" => false, "query" => $q, "error" => $th->errorInfo];
        }
    }
    static function checkTableExists(String $table): Bool
    {
        $db = new DB();
        $conn = $db->Conectar();

        $q = "SELECT * from INFORMATION_SCHEMA.TABLES where TABLE_NAME = '{$table}'";
        $query = $conn->query($q);
        $count = $query->fetchColumn();

        return !empty($count) ? true : false;
    }

    /**
     * @param String $classname nombre de la clase
     * @param Bool $is_static boolean para obtener valores todos metodos (true - solo los static, false - public)
     * @return Array retorna un arreglo con los nombres de cada metodo de la clase que selecionaron
     */
    static function getClassMethods(String $classname = "AutomaticForm", Bool $is_static = true): array
    {
        $reflection = new ReflectionClass($classname);
        if ($is_static) {
            return json_decode(json_encode($reflection->getMethods(ReflectionMethod::IS_STATIC), JSON_UNESCAPED_UNICODE), true);
        } else {
            return json_decode(json_encode($reflection->getMethods(ReflectionMethod::IS_PUBLIC), JSON_UNESCAPED_UNICODE), true);
        }
    }

    /**
     * https://php.watch/versions/8.2/utf8_encode-utf8_decode-deprecated#:~:text=Replacements%20for%20utf8_decode,intl%20extension%2C%20or%20iconv%20extension.
     */
    static function iso8859_1_to_utf8(string $s): string
    {
        $s .= $s;
        $len = \strlen($s);

        for ($i = $len >> 1, $j = 0; $i < $len; ++$i, ++$j) {
            switch (true) {
                case $s[$i] < "\x80":
                    $s[$j] = $s[$i];
                    break;
                case $s[$i] < "\xC0":
                    $s[$j] = "\xC2";
                    $s[++$j] = $s[$i];
                    break;
                default:
                    $s[$j] = "\xC3";
                    $s[++$j] = \chr(\ord($s[$i]) - 64);
                    break;
            }
        }

        return substr($s, 0, $j);
    }

    /**
     * https://php.watch/versions/8.2/utf8_encode-utf8_decode-deprecated#:~:text=Replacements%20for%20utf8_decode,intl%20extension%2C%20or%20iconv%20extension.
     */
    static function utf8_to_iso8859_1(string $string): string
    {
        $s = (string) $string;
        $len = \strlen($s);

        for ($i = 0, $j = 0; $i < $len; ++$i, ++$j) {
            switch ($s[$i] & "\xF0") {
                case "\xC0":
                case "\xD0":
                    $c = (\ord($s[$i] & "\x1F") << 6) | \ord($s[++$i] & "\x3F");
                    $s[$j] = $c < 256 ? \chr($c) : '?';
                    break;

                case "\xF0":
                    ++$i;
                    // no break

                case "\xE0":
                    $s[$j] = '?';
                    $i += 2;
                    break;

                default:
                    $s[$j] = $s[$i];
            }
        }

        return substr($s, 0, $j);
    }
}
