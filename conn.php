<?php

use Model\DB;

# connect to database
$db = new DB(DATABASE["GESTOR"]);
$conn = $db->connect(false);
$error = DB::getError($conn);

if ($error && DATABASE["GESTOR"] === "SQLSRV") {
    foreach ([
        "/db/DDL.sql",
        "/db/DML.sql"
    ] as $filename) {
        $sqlScript = FOLDER_SIDE . $filename;
        $hostname = $db->getParams("params")["hostname"];
        $username = $db->getParams("params")["username"];
        $password = $db->getParams("params")["password"];
        $db->executeSQLScript($sqlScript, DB::pdo_connect("sqlsrv:Server={$hostname}", $username, $password));
    }
    session_destroy();
}
