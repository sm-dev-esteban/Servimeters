<?php

require_once('../../model/TemplateReport.model.php');

function executeReport($fechaInicio, $fechaFin, $action){
    $report = new TemplateReport();
    $result = $report->$action($fechaInicio, $fechaFin);
    return $result;
}

