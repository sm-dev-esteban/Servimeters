<?php
include("../controller/automaticForm.php");
// prueba manual para pruebas en general
// data[] // datos en general de cualquer tipo
// file[] // datos en general de cualquer tipo
$pruebaAF1 = new AutomaticForm(["data" => ["one" => "one"]], "prueba1", "INSERT");

$line = unaLiniaPoFavo(250) . "<br>";

print "<pre>";
print_r($pruebaAF1->getParams());
print "</pre>";

function unaLiniaPoFavo($count)
{
    $line = "";
    for ($i = 0; $i < $count; $i++) {
        $line .= "-";
    }
    return $line;
}
