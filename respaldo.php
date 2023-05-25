<?php
if (isset($_GET["respaldo"])) {
    // codigo de prueba para ver que tan viable es realizar una copia de seguridad de la base de datos
    // teoricamente esto lo ejecutaria un crom cada no se cuanto

    if (isset($_POST["timezone"]) && !empty($_POST["timezone"])) {
        $timezone = $_POST["timezone"];
    } else {
        $dateTime = new DateTime();
        $timezone = $dateTime->getTimezone()->getName();
    }

    date_default_timezone_set($timezone);

    echo $timezone, "<br>";

    $date = date("Y-m-d");
    $fullDate = date("Y-m-d H:i:s");

    echo $fullDate, "<br>";

    $server_P = strtolower(explode("/", $_SERVER["SERVER_PROTOCOL"])[0]); // protocolo
    $server_N = $_SERVER["SERVER_NAME"]; // nombre del servidor

    $hostname = $server_N;
    $username = "sa";
    $password = "Es123456*";
    $database = "HorasExtra";

    // $conn = new mysqli($hostname, $username, $password, $database);
    $conn = sqlsrv_connect($hostname, ["DATABASE" => $database, "UID" => $username, "PWD" => $password]);

    // $server_1 = "$server_P://$server_N/libreria/respaldo/$date"; // folder
    // $server_2 = "{$_SERVER["DOCUMENT_ROOT"]}/libreria/respaldo/$date"; // folder
    $server_2 = __DIR__ . "/respaldo/$date"; // folder


    if (!file_exists($server_2)) {
        mkdir($server_2, 0777, true);
    }

    // ddl
    $file_ddl = "ddl.sql";
    echo $server_2, "/", $file_ddl, "<br>";
    $open_ddl = fopen("$server_2/$file_ddl", "w");
    // $queryTables = sqlsrv_query($conn, "SHOW TABLES FROM `{$database}`");
    $queryTables = sqlsrv_query($conn, "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_CATALOG = '{$database}'");

    fwrite($open_ddl, "-- version 0.0.1" . "\n");
    fwrite($open_ddl, "-- Date: $fullDate" . "\n");
    fwrite($open_ddl, "-- timeZone: $timezone" . "\n");

    fwrite($open_ddl, "-- hostname: $hostname" . "\n");
    fwrite($open_ddl, "-- username: $username" . "\n");
    fwrite($open_ddl, "-- password: $password" . "\n");
    fwrite($open_ddl, "-- database: $database" . "\n");

    fwrite($open_ddl, "-- countTables: " . (sqlsrv_num_rows($queryTables) == false ? "pepino encebollado" : sqlsrv_num_rows($queryTables)) . "\n");

    fwrite($open_ddl, str_replace("DATABASES", $database, "CREATE DATABASE [DATABASES]") . "\n");
    fwrite($open_ddl, "GO\n\n");
    while ($datTables = sqlsrv_fetch_array($queryTables, SQLSRV_FETCH_ASSOC)) {
        $queryColumn = sqlsrv_query($conn, "exec sp_columns {$datTables["TABLE_NAME"]}");
        $x = [];
        while ($datColumn = sqlsrv_fetch_array($queryColumn, SQLSRV_FETCH_ASSOC)) {
            $x[] = "\t{$datColumn["COLUMN_NAME"]}" . str_replace("identity", "IDENTITY(1, 1)", " {$datColumn["TYPE_NAME"]} ({$datColumn["PRECISION"]}); " . ($datColumn["NULLABLE"] == 1 ? "NULL" : "NOT NULL"));
        }
        fwrite($open_ddl, str_replace("TABLE_SCHEMA", $datTables["TABLE_SCHEMA"], str_replace("TABLE_NAME", $datTables["TABLE_NAME"], "CREATE TABLE [TABLE_SCHEMA].[TABLE_NAME] (\n" . implode(",\n", $x) . "\n)\n")));
        fwrite($open_ddl, "GO\n\n");
    }
    fclose($open_ddl);
    // ddl

    // dml
    $file_dml = "dml.sql";
    $open_dml = fopen("$server_2/$file_dml", "w");
    echo $server_2, "/", $file_dml, "<br>";
    $queryTables = sqlsrv_query($conn, "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_CATALOG = '{$database}'");

    fwrite($open_dml, "-- version 0.0.1" . "\n");
    fwrite($open_dml, "-- Date: $fullDate" . "\n");
    fwrite($open_dml, "-- timeZone: $timezone" . "\n");

    fwrite($open_dml, "-- hostname: $hostname" . "\n");
    fwrite($open_dml, "-- username: $username" . "\n");
    fwrite($open_dml, "-- password: $password" . "\n");
    fwrite($open_dml, "-- database: $database" . "\n");

    fwrite($open_dml, "-- countTables: " . (sqlsrv_num_rows($queryTables) == false ? "pepino encebollado" : sqlsrv_num_rows($queryTables)) . "\n");

    while ($datTables = sqlsrv_fetch_array($queryTables, SQLSRV_FETCH_ASSOC)) {
        $query = sqlsrv_query($conn, "SELECT * FROM {$datTables["TABLE_NAME"]}");
        $queryColumn = sqlsrv_query($conn, "exec sp_columns {$datTables["TABLE_NAME"]}");
        $k = [];
        $v = [];
        $tv = [];
        while ($dat = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            while ($datColumn = sqlsrv_fetch_array($queryColumn, SQLSRV_FETCH_ASSOC)) {
                $k[] = $datColumn["COLUMN_NAME"];
                if ($datColumn["TYPE_NAME"] == "datetime") {
                    $d = $dat[$datColumn["COLUMN_NAME"]];
                    $t = $d->format("Y-m-d H:i:s");
                    $tv[] = "'{$t}'";
                } else if ($datColumn["TYPE_NAME"] == "date") {
                    $d = $dat[$datColumn["COLUMN_NAME"]];
                    $t = $d->format("Y-m-d");
                    $tv[] = "'{$t}'";
                } else {
                    $tv[] = "'{$dat[$datColumn["COLUMN_NAME"]]}'";
                }
            }
            $v[] = "\t(" . implode(", ", $tv) . ")";
        }
        fwrite($open_dml, str_replace("TABLE_SCHEMA", $datTables["TABLE_SCHEMA"], str_replace("TABLE_NAME", $datTables["TABLE_NAME"], "INSERT INTO \n\t[TABLE_SCHEMA].[TABLE_NAME] (" . implode(", ", $k) . ")\nvalues \n" . implode(",\n", $v) . "\n")));
        fwrite($open_dml, "GO\n\n");
    }


    // INSERT INTO TiposHE (nombre) values ('prueba')

    fclose($open_dml);
    // dml
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba de respaldo</title>
    <link rel="stylesheet" href="./AdminLTE/dist/css/adminlte.min.css">
</head>

<body>
    <div class="container py-3"></div>
</body>

<foot>
    <script src="./AdminLTE/plugins/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax("?respaldo=true", {
                type: "POST",
                dataType: "HTML",
                data: {
                    timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                },
                success: function(response) {
                    $(".container").html(response);
                }
            });
        });
    </script>
</foot>

</html>