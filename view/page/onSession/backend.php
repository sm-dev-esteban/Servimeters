<?php session_start();

use Controller\SeeApplicationReport;
use Controller\SeeHoursReport;
use Model\LDAP;

include_once "{$_SESSION["FOLDER_SIDE"]}/vendor/autoload.php";
include "{$_SESSION["FOLDER_SIDE"]}/Config.php";
include "{$_SESSION["FOLDER_SIDE"]}/conn.php";

switch (strtoupper($_REQUEST["action"] ?? false)) {
    case 'REPORTHE':
        echo str_replace(
            "card card-primary card-outline",
            "modal-body card card-primary card-outline",
            SeeHoursReport::viewHoursReport($_POST["id"])
        );
        exit;
        break;
    case 'REPORTSP':
        echo str_replace(
            "card card-primary card-outline",
            "modal-body card card-primary card-outline",
            SeeApplicationReport::viewApplicationReport($_POST["id"])
        );
        exit;
        break;
    case 'COMMENTSHE':
        echo json_encode($db->executeQuery(<<<SQL
            select * from HorasExtras_Comentario where id = :id
        SQL, [":id" => base64_decode($_POST["id"] ?? base64_encode(false))]));
        exit;
        break;
    case 'LDAPFIND':
        $data = [
            $_SESSION["userSession"] ?? false,
            $_SESSION["passSession"] ?? false,
            $_REQUEST["search"] ?? false,
            $_REQUEST["filter"] ?? false,
            [
                "LDAP_OPT_SIZELIMIT" => $_REQUEST["limit"] ?? false
            ]
        ];
        try {
            $LDAP = new LDAP();

            $dn = "SERVIMETERSSA";

            $LDAP->uri = "servimeterssa.com";
            $LDAP->dn = "{$dn}\\";
            $LDAP->base = "DC={$dn},DC=COM";

            $response = $LDAP->connect(...$data);
            unset($response["count"]); // >:(
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } catch (Exception $th) {
            echo json_encode([
                "error" => $th->getMessage(),
                $data
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
        break;
    case 'XLS':
        $DATA = json_decode(trim(file_get_contents("php://input")));

        $param = $DATA->param ?? "Error send request";
        $content = $DATA->content ?? "Error send request";
        $title = $param->title ?? false;

        // header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=" . ($param->filename ?? "Error") . "");

        $title = ($title != false && $title != "false" ? <<<HTML
            <caption>{$title}</caption>
        HTML : "");

        echo <<<HTML
            <table>
                {$title}
                {$content}
            </table>
        HTML;
        exit;
        break;
    default:
        echo json_encode(["error" => "action is undefined"]);
        break;
}
