<?php

require_once('../model/Repository.model.php');

switch ($_GET['action']) {
    case 'execute':
        if (!isset($_POST['object'])) {
            echo 'Faltan datos';
            return;
        }
        $model = $_GET['model'];
        $action = $_GET['crud'];
        $repository = new Repository($model);
        echo $repository->execute($_POST['object'], $action);
        exit();
        break;
    case 'listAll':
        $model = $_GET['model'];
        $action = $_GET['crud'];
        $repository = new Repository($model);
        $result = $repository->get($action);
        echo json_encode($result);
        exit();
        break;
    case 'insertMany':
        if (!isset($_POST['data'])) {
            echo 'Faltan datos';
            return;
        }
        $model = $_GET['model'];
        $action = $_GET['crud'];
        $repository = new Repository($model);
        echo $repository->execute($_POST['data'], $action);
        exit();
        break;
    default:
        echo 'no hay valores';
        break;
}