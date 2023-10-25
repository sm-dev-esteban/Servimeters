<?php

namespace Model;

use Model\DBConfig;
use PDO;
use Exception;
use PDOException;

/**
 * @author Esteban Serna Palacios 😉😜
 */
class DB extends DBConfig
{
    private $gestor, $params, $con, $dns;
    private const DEFAULT_PARAMS = [
        "hostname" => DATABASE["HOSTNAME"],
        "username" => DATABASE["USERNAME"],
        "password" => DATABASE["PASSWORD"],
        "database" => DATABASE["DATABASE"],
        "port" => DATABASE["PORT"],
        "file" => DATABASE["FILE"]
    ];

    public function __construct(
        String $gestor = DATABASE["GESTOR"],
        array $params = []
    ) {
        $this->params = array_merge(self::DEFAULT_PARAMS, $params);
        $this->gestor = strtoupper($gestor);
        $this->con = false;

        $this->dns = [
            "MYSQL" => "mysql:host={$this->params["hostname"]};dbname={$this->params["database"]};port={$this->params["port"]}",
            "SQLSRV" => "sqlsrv:Server={$this->params["hostname"]};Database={$this->params["database"]}",
            "SQLITE" => "sqlite:{$this->params["file"]}"
        ];
    }

    static function getError(
        Mixed $error,
        Bool $returnArray = false
    ) {
        # error code
        $c = (is_object($error) && method_exists(
            $error,
            "getCode"
        ) ? $error->getCode() : false);
        # error message
        $m = (is_object($error) && method_exists(
            $error,
            "getMessage"
        ) ? $error->getMessage() : (is_array($error) && isset($error["error"]) ? $error["error"] : (is_string($error) ? $error : false)));

        return ($returnArray ? [
            "error" => $m,
            "code" => $c
        ]  :  $m);
    }

    static function getType(mixed $str)
    {
        $r = gettype($str);

        if ($r !== "array") return $r;
        else {
            $bandera = false;

            foreach ($str as $key => $value) {
                if (is_numeric($key)) continue;
                else {
                    $bandera = true;
                    break;
                }
            }

            if ($bandera === true) return "array of objects";
            else return "array";
        }
    }

    /**
     * @param Mixed $name - Nombre del parámetro que quieran obtener por defecto los devuelve todos
     * @return Mixed Devuelve cualquier valor que este en el __construct
     */
    public function getParams(String|Bool $name = false): String|array|PDO
    {
        // return $name ? ($this->$name ?? false) : $this;
        if ($name) $r = $this->$name ?? false;
        else foreach ($this as $key => $value) $r[$key] = $value;

        return $r;
    }

    /**
     * @return Mixed Retorna la conexión a la base de datos o un arreglo con el error
     */
    public function connect($createDatabase = false)
    {
        if (!in_array($this->gestor, array_keys($this->dns))) return self::getError("administrator not configured: {$this->gestor}", true);

        try {
            $this->con = self::pdo_connect($this->dns[$this->gestor], $this->params["username"], $this->params["password"]);
        } catch (PDOException $th) {

            if ($createDatabase === true) return self::createDatabase() ? self::connect() : self::getError($th, true);
            return self::getError($th, true);
            // throw new Exception($th, 1);
        }
        return $this->con;
    }

    static function pdo_connect(string $dsn, string|null $username = null, string|null $password = null, array|null $options = null)
    {
        return new PDO($dsn, $username, $password, $options);
    }

    /**
     * @param String $query
     * 
     * @return array
     */
    public function createDatabase()
    {
        switch ($this->gestor) {
            case "MYSQL":
            case "SQLSRV":
                $dns = "{$this->gestor}:" . ($this->gestor == "MYSQL" ? "host" : "Server") . "={$this->params["hostname"]};";
                try {
                    $tempCon = new PDO($dns, $this->params["username"], $this->params["password"]);
                } catch (PDOException $th) {
                    return false;
                }
                $query = self::executeQuery("CREATE DATABASE IF NOT EXISTS :DATABASE", [":DATABASE" => $this->params["database"]], $tempCon);
                return (self::getError($query) === false ? true : false);
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * @param String $table Nombre de la tabla
     * @param PDO $con Conexión de PDO. Por defecto usara la conexión de la clase
     * @return Bool Booleano si se ejecuto o no
     */
    public function createTable(String $table, Mixed $con = false): Bool
    {
        $arrayQuery = [
            "MYSQL" => <<<SQL
            CREATE TABLE IF NOT EXISTS `{$table}` (
                id INT AUTO_INCREMENT PRIMARY KEY,
                fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP
            )
            SQL,
            "SQLSRV" => <<<SQL
            IF NOT EXISTS (SELECT * FROM sysobjects WHERE name = '{$table}' and xtype = 'U')
            CREATE TABLE {$table} (
                id INT IDENTITY(1,1) PRIMARY KEY,
                fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP
            )
            SQL,
            "SQLITE" => <<<SQL
            CREATE TABLE `{$table}` (
                id INT AUTOINCREMENT PRIMARY KEY,
                fechaRegistro TEXT DEFAULT CURRENT_TIMESTAMP
            )
            SQL
        ];
        $query = $arrayQuery[$this->gestor] ?? "";
        $con = ($con === false ? $this->con : $con);
        return is_array(self::executeQuery($query)) ? false : true;
    }

    /**
     * @param String $table Nombre de la tabla
     * @param String $name Nombre de la columna que quieren crear en la tabla
     * @param String $type Tipo de dato
     * @param PDO $con Conexión de PDO. Por defecto usara la conexión de la clase
     * @return Bool Booleano si se ejecuto o no
     */
    public function createColumn(String $table, String $name, String $type = "TEXT DEFAULT NULL", Mixed $con = false): Bool
    {
        if ($this->gestor == "SQLSRV" && $type == "TEXT DEFAULT NULL") $type = "VARCHAR(MAX) DEFAULT NULL";

        $arrayQuery = [
            "MYSQL" => <<<SQL
            ALTER TABLE `$table` ADD COLUMN IF NOT EXISTS `$name` $type
            SQL,
            "SQLSRV" => <<<SQL
            IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND COLUMN_NAME = '$name')
                BEGIN
                ALTER TABLE $table ADD $name $type
            END
            SQL,
            "SQLITE" => <<<SQL
            ALTER TABLE `$table` ADD COLUMN `$name` $type
            SQL
        ];
        $query = $arrayQuery[$this->gestor] ?? "";
        $con = ($con === false ? $this->con : $con);
        return is_array(self::executeQuery($query)) ? false : true;
    }

    /**
     * @param String $query Recibe una consulta de toda la vida, pero al ejecutarla con esta función retorna un error dado el caso para una validación más fácil.
     * @param Array $prepare Arreglo para consultas preparadas.
     * @param PDO $con Conexión de PDO. Por defecto usará la conexión de la clase.
     * @return Mixed 
     * 1. UPDATE|INSERT|DELETE retorna true 
     * 2. SELECT (Cualquier consulta que retorne filas) retorna los datos
     * 3. Devuelve un arreglo con el error
     */
    public function executeQuery(String $query, array $prepare = [], Mixed $con = false)
    {
        $con = ($con === false ? $this->con : $con);

        if (!$con) return self::getError("No connection to database", true);
        else if (!is_object($con)) return self::getError("Connect no valid" . var_dump($con), true);

        try { // Válida si se ejecuta la consulta

            // (☞ﾟヮﾟ)☞ Envíen bien los arreglos. ☜(ﾟヮﾟ☜)
            $options = self::getType($prepare) == "array of objects" ? [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY] : [];

            $execute = $con->prepare($query, $options);
            $execute->execute($prepare);

            try { // Válida si contiene datos
                return $execute->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $th) {
                return true;
            }
        } catch (PDOException $th) { // Si la consulta tiene algun error lo retorna
            return self::getError($th, true);
        }
        // El que lea esto es gay n:
    }

    public function executeSQLScript($file_script = "", $conn = false)
    {
        if (file_exists($file_script)) {
            foreach (explode(";", file_get_contents($file_script)) as $script)
                echo var_dump(self::executeQuery(trim($script), [], $conn)), "<br>", "\n";
            return true;
        } else
            return false;
    }
}
