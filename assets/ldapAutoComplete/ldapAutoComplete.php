<?php

use Config\AutoComplete;

include_once dirname(dirname(__DIR__)) . "/vendor/autoload.php";

$autoComplete = new AutoComplete;

$limit = $_POST["limit"] ?? false;
$column = $_POST["column"] ?? false;
$search = $_POST["search"] ?? false;

try {
    echo json_encode($autoComplete->ldap($search, $column, $limit));
} catch (Exception | Error $th) {
    $type = $th instanceof Exception ? "Exception" : "Error";
    echo json_encode([$type => $th->getMessage()]);
}
