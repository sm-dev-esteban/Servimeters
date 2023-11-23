<?php session_start();

include "{$_SESSION["FOLDER_SIDE"]}/Config.php";
include_once "{$_SESSION["FOLDER_SIDE"]}/vendor/autoload.php";

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
