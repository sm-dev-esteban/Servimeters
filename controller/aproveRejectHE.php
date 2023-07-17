<?php
session_start();
include_once("automaticForm.php");

$mail = $_SESSION["email"] ?? false;
$usuario = $_SESSION["usuario"] ?? false;

$rol = $_SESSION["rol"] ?? false;
$gestion = $_SESSION["gestion"] ?? false;

$id_aprobador = $_SESSION["rol"] ?? false;

$selecionados = AutomaticForm::getDataSql("ReportesHE", "check_user = ");
