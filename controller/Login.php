<?php

namespace Controller;

use Exception;
use Model\LDAP;


class Login
{
    static function init_session(String $user, String $pass)
    {
        try {
            $LDAP = new LDAP();
            $ldap_result = $LDAP->connect($user, $pass, "*{$user}*", ["name", "mail", "SAMAccountName", "cn", "distinguishedname", "samaccountname"]);

            $_SESSION["infoUsuario"] = $ldap_result;

            $_SESSION["email"] = $ldap_result[0]["mail"][0] ?? "undefined";
            $_SESSION["usuario"] = $ldap_result[0]["cn"][0] ?? "undefined";
            $_SESSION["BuscarArea"] = $ldap_result[0]["distinguishedname"][0] ?? "undefined";
            $_SESSION["usuarioRegistro"] = $ldap_result[0]["samaccountname"][0] ?? "undefined";

            $_SESSION["estadoAutentica"] = "conectado";

            $_SESSION["userSession"] = $user;
            $_SESSION["passSession"] = $pass;

            $_SESSION["isApprover"] = self::isApprover();
        } catch (Exception $th) {
            return [
                "status" => false,
                "error" => $th->getMessage()
            ];
        }
        return [
            "status" => true,
            "error" => false
        ];
    }

    static function isApprover(): Bool
    {
        include "C:/xampp/htdocs/MVC/conn.php";

        $email = $_SESSION["email"] ?? false;
        $dataA = $db->executeQuery(<<<SQL
            select top 1
            A.id, A.mail, B.nombre type, C.nombre manages, D.nombre isAdmin, E.nombre staffRequest
            from Aprobadores A
            inner join HorasExtras_Aprobador_Tipo B on A.id_tipo = B.id
            inner join HorasExtras_Aprobador_Gestiona C on A.id_gestiona = C.id
            inner join HorasExtras_Aprobador_Administra D on A.id_Esadmin = D.id
            inner join HorasExtras_Aprobador_SolicitudPersonal E on A.id_solicitudPersonal = E.id
            where A.mail = '{$email}'
        SQL);

        $error = $db::getError($dataA);

        if (!$error && !empty(count($dataA))) {
            foreach ($dataA as $data)
                foreach ($data as $k => $v)
                    $_SESSION[$k] = $v;
            return true;
        } else
            return false;
    }
}
