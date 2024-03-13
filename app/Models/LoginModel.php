<?php

namespace Model;

use Config\LDAP;
use Controller\Aprobador;
use Exception;
use PDOException;

class LoginModel extends Aprobador
{
    private $LDAP;

    public function __construct()
    {
        parent::__construct();
        $this->LDAP = new LDAP;
    }

    /**
     * Starts a user session based on the provided username and password.
     *
     * @param string $user Username
     * @param string $pass Password
     *
     * @return array
     * @throws Exception
     */
    public function newSession($user, $pass): array
    {
        try {
            $columns = [
                "nombre" => "name",
                "email" => "mail",
                "usuario" => "cn",
                "BuscarArea" => "distinguishedname",
                "usuarioRegistro" => "samaccountname"
            ];

            $result = $this->LDAP
                ->bind($user, $pass)
                ->search("(samaccountname=*{$user}*)", array_values($columns));

            foreach ($columns as $keySession => $keyLDAP)
                $_SESSION[$keySession] = isset($result[0][$keyLDAP])
                    ? ($result[0][$keyLDAP][0] ?? false)
                    : false;

            $_SESSION["isApprover"] = self::validateApprover(["email" => $_SESSION["email"]]);
            $_SESSION["SESSION_MODE"] = "AdminMode";

            $_SESSION["user"] = $user;
            $_SESSION["pass"] = $pass;

            $_SESSION["session_start"] = time();
            // $_SESSION["session_end"] = null;
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

    private function authenticateApprover(array $arrayFilter): array
    {
        try {
            $filter = implode(" AND ", array_map(function ($k, $v) {
                return "{$k} = '{$v}'";
            }, array_keys($arrayFilter), array_values($arrayFilter)));

            return $this->getApprover($filter);
        } catch (PDOException $th) {
            throw new Exception("Error authenticating user: {$th->getMessage()}");
        }
    }

    private function validateApprover(array $find): bool
    {
        $dataApprover = self::authenticateApprover($find);

        if (empty($dataApprover)) return false;

        foreach ($dataApprover as $data) foreach ($data as $key => $value) $_SESSION[$key] = $value;

        return true;
    }

    /**
     * Inserta datos y aparte crea la tabla junto con las columnas recibidas
     * 
     * @param string $table
     * @param array $initialData
     * 
     */
    protected function insertInitialDataForLogin(string $table, array $initialData = []): void
    {
        try {
            foreach ($initialData as $data) $this->prepare($table, ["data" => $data])->insert();
        } catch (Exception $th) {
            throw new Exception("Ocurrio un error creando la tabla {$table}: {$th->getMessage()}");
        }
    }

    public static function destroySession(): void
    {
        session_destroy();
    }
}
