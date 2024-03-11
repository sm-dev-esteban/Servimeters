<?php

namespace Config;

use PDO;
use Exception;
use InvalidArgumentException;

use System\Config\AppConfig;

class ProcessData
{
    # Properties
    private $table, $query, $prepareData;
    private array $data, $file;

    /**
     * @param string $filePath
     * Carpeta donde se guarda los archivos
     * 
     * Palabras especiales
     * * @ID        -> id
     * * @TABLE     -> tabla
     * * @DATE      -> fecha: Y-m-d
     * * @HOUR      -> hora: H:i:s
     * * @FULLDATE  -> fechaCompleta: Y-m-d H:i:s
     */
    public string $filePath = "@TABLE/@ID";

    private array $pendingFiles = [];

    public bool $autoCreation = true;
    public bool $checkEmptyValues = false;

    protected bool $isPrepared = false;
    public $conn, $tableManager, $imageProcessor;

    # Image processing options
    public bool $OPTIMIZE_IMAGES = false;
    public int $DEFAULT_QUALITY = 80;
    public bool $USE_RELATIVE_PATH = true;

    # Constructor
    public function __construct(PDO $conn = null)
    {
        $this->conn = new DB();
        $this->conn->CREATE_DATABASE = !AppConfig::PRODUCTION;

        if ($conn !== null) $this->conn->setConn($conn);
        else $this->conn->connect();

        $this->imageProcessor = new ImageProcessor();
        $this->tableManager = new TableManager($this->conn->getConn());
    }

    # Prepare data for insert/update
    public function prepare(string $table, array $data): self
    {
        $this->isPrepared = true;
        $this->prepareData = [];

        $this->table = $table;
        $this->data = $data["data"] ?? [];
        $this->file = $data["file"] ?? [];

        return $this;
    }

    # Insert data into the database
    public function insert(): array
    {
        try {
            if ($this->isPrepared) $this->conn->executeQuery(self::formatQuery("INSERT")->query, $this->prepareData);
            else throw new Exception("The operation is not prepared.");
        } catch (Exception $th) {
            throw $th;
        }

        $response = [
            "lastInsertId" => $this->conn->getConn()->lastInsertId(),
            "query" => $this->query,
        ];

        self::movePendingFiles($response["lastInsertId"]);

        return $response;
    }

    # Update data in the database
    public function update($condition): array
    {
        try {
            if (empty($condition)) throw new InvalidArgumentException("Condition is mandatory for updating.");

            if ($this->isPrepared) $count = $this->conn->executeQuery(self::formatQuery("UPDATE", $condition)->query, $this->prepareData);
            else throw new Exception("The operation is not prepared.");
        } catch (Exception $th) {
            throw $th;
        }

        $response = [
            "rowCount" => $count,
            "query" => $this->query
        ];

        $id = null;

        $rCondition = strtolower(str_replace(" or ", " and ", str_replace("\n", " ", $condition)));

        foreach (explode(" and ", $rCondition) as $cond) {
            $condExpl = explode("=", $cond);
            $key = trim(strtolower($condExpl[0]));
            $value = trim(strtolower($condExpl[1]));

            if ($key != "id") continue;
            $id = $value;
            break;
        }

        self::movePendingFiles($id);

        return $response;
    }

    # Format SQL query based on data and file information
    private function formatQuery($type, $condition = ""): self
    {
        $pData = self::processData();
        $pFile = self::processFile();

        $keys = array_merge($pData["keys"], $pFile["keys"]);
        $values = array_merge($pData["values"], $pFile["values"]);

        if (!AppConfig::PRODUCTION && $this->autoCreation === true) self::autoCreate($this->table, $keys);

        $this->query = [
            "INSERT" => "INSERT INTO {$this->table} (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $values) . ")",
            "UPDATE" => "UPDATE {$this->table} SET " . implode(", ", array_map(function ($k, $v) {
                return "{$k} = {$v}";
            }, $keys, $values)) . " WHERE {$condition}",
        ][$type] ?? "";

        return $this;
    }

    private function jsonUnescapedUnicode(array $array): string
    {
        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }

    # Process data to be inserted into the database
    private function processData(): array
    {
        $data = [];
        $data["keys"] = [];
        $data["values"] = [];

        if (!empty(count($this->data))) foreach ($this->data as $name => $value) if ($this->checkEmptyValues === true ? !empty($value) : true) {
            $data["keys"][] = $this->conn->getGestor() === "SQLSRV" ? "[{$name}]" : $name;
            $data["values"][] = ":{$name}";

            $value = is_array($value) ? self::jsonUnescapedUnicode($value) : $value;

            $this->prepareData[":{$name}"] = $value;
        }

        return $data;
    }

    # Process file data for file uploads
    private function processFile(): array
    {
        $data = [];
        $data["keys"] = [];
        $data["values"] = [];

        if (!empty(count($this->file))) foreach ($this->file["name"] as $name => $value) {
            # Load the files
            if (is_array($value)) for ($i = 0; $i < count($value); $i++) if (!empty($this->file["tmp_name"][$name][$i])) {
                $value[$i] = "{$this->filePath}/{$value[$i]}";

                # Pending Files
                $this->pendingFiles[] = [
                    "from" => $this->file["tmp_name"][$name][$i],
                    "to" => $value[$i]
                ];
            } else {
                $value = "{$this->filePath}/{$value}";

                # Pending Files 
                $this->pendingFiles[] = [
                    "from" => $this->file["tmp_name"][$name],
                    "to" => $value
                ];
            }

            $data["keys"][] = $this->conn->getGestor() === "SQLSRV" ? "[{$name}]" : $name;
            $data["values"][] = ":{$name}";

            $value = is_array($value) ? self::jsonUnescapedUnicode(array_map(function ($v) {
                return str_replace(AppConfig::BASE_FOLDER, AppConfig::BASE_SERVER, $v);
            }, $value)) : str_replace(AppConfig::BASE_FOLDER, AppConfig::BASE_SERVER, $value);

            if ($this->USE_RELATIVE_PATH === true) $value = str_replace(AppConfig::BASE_SERVER, "", $value);

            $this->prepareData[":{$name}"] = $value;
        }

        return $data;
    }

    # Move files once registered or updated to the "path/table/id/file" folder
    private function movePendingFiles($id = null): void
    {
        if ($this->pendingFiles) foreach ($this->pendingFiles as $data) {
            $from = $data["from"] ?? null;
            $to   = $data["to"]   ?? null;

            $to = str_replace([
                "\\",
                "@ID",
                "@TABLE",
                "@DATE",
                "@HOUR",
                "@FULLDATE"
            ], [
                "/",
                (string)$id,
                $this->table,
                date("Y-m-d"),
                date("H:i:s"),
                date("Y-m-d H:i:s")
            ], $to);

            $to = AppConfig::BASE_FOLDER_FILE . "/" . trim($to, "/");

            $path = dirname($to);

            # Create a folder if it does not exist
            if (!file_exists($path)) @mkdir($path, 0777, true);

            # Move pending files
            if ($from !== null && $to !== null) @move_uploaded_file($from, $to);

            # Image optimization
            if ($this->OPTIMIZE_IMAGES === TRUE) $this->imageProcessor::optimizeImages(
                format: $to,
                quality: $this->DEFAULT_QUALITY
            );
        }
    }

    # Create the tables and columns.
    private function autoCreate(string $table, array $columns): void
    {
        $this->tableManager->createTable($table);

        foreach ($columns as $column) $this->tableManager->createColumn($table, str_replace(["[", "]"], "", $column));
    }

    # Destructor to close the database connection
    public function __destruct()
    {
        $this->conn->close();
    }
}
