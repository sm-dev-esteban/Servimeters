<?php session_start();

include "C:/xampp/htdocs/servimeters/Config.php";
include_once "C:/xampp/htdocs/servimeters/vendor/autoload.php";

use Controller\Login;

if ($_GET["action"] ?? false) switch ($_GET["action"]) {
    case 'login':
        $user = $_REQUEST["user"] ?? false;
        $pass = $_REQUEST["pass"] ?? false;
        echo json_encode(Login::init_session($user, $pass), JSON_UNESCAPED_UNICODE);
        break;
    default:
        throw new Exception("action is undefined", 1);
        break;
}

exit();
