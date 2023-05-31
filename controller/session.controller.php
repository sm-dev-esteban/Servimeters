<?php

require_once('../config/Session.config.php');
require_once "../config/LoadConfig.config.php";
$config = LoadConfig::getConfig();

$session = new Sesion();

function validateRole()
{
    require_once('../model/Aprobador.model.php');

    $validate = new Aprobador();
    $user = $validate->getPermisos($_SESSION["usuario"]);

    if (!empty($user)) {
        echo $user;
        exit();
    }
}

switch ($_GET['action']) {
    case 'init':
        $isSession = $session->init_session($_POST['user'], $_POST['pass']);
        if ($isSession) {
            echo $isSession;
            exit();
        }
        echo false;
        break;
    case 'finish':

        session_destroy();
        header('Location:' . $config->URL_SITE);
        break;
    case 'validateRole':
        validateRole();
        break;
    default:
        echo '';
        break;
}
