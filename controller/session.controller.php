<?php

require_once('../config/Session.config.php');
require_once "../config/LoadConfig.config.php";
$config = LoadConfig::getConfig();

$session = new Sesion();

function validateRole()
{
    require_once('../model/Aprobador.model.php');

    $validate = new Aprobador();
    $user = $validate->getPermisos($_SESSION["email"]);

    return $user;
}

switch ($_GET['action']) {
    case 'init':
        $isSession = $session->init_session($_POST['user'], $_POST['pass']);
        if ($isSession["status"] == true) {
            echo json_encode(["status" => true, "rol" => validateRole()], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode($isSession, JSON_UNESCAPED_UNICODE);
        }
        exit();
        break;
    case 'finish':
        session_destroy();
        header('Location:' . $config->URL_SITE);
        break;
    default:
        echo '';
        break;
}
