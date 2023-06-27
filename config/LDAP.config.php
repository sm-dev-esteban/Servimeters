<?php

class LDAP
{

    private const DOMINIO = 'SERVIMETERSSA.COM';
    private const DN = 'dc=SERVIMETERSSA,dc=COM';
    private const PUERTO = 389;
    private $connection;
    private $result;


    public function connectAD($user, $pass)
    {
        $return = [];
        $this->result = "";
        try {
            $this->connection = ldap_connect(self::DOMINIO, self::PUERTO);
            ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);

            try {
                $confirmacionUser = @ldap_bind($this->connection, "SERVIMETERSSA\\{$user}", $pass);
                if (!$confirmacionUser) {
                    $return = ["status" => false, "data" => $this->result, "error" => "Credentials error"];
                    return $return;
                }
            } catch (Exception $th) {
                $return = ["status" => false, "data" => $this->result, "error" => "Bind: {$th->getMessage()}"];
                return $return;
            }

            $filter = "(|(SAMAccountName=" . $user . "))";
            $searchUser = ldap_search($this->connection, self::DN, $filter);
            $resultSearch = ldap_get_entries($this->connection, $searchUser);
            $this->result = $resultSearch;

            ldap_close($this->connection);
            $return = ["status" => true, "data" => $this->result, "error" => false];
        } catch (Exception $th) {
            $return = ["status" => false, "data" => $this->result, "error" => "Connect: {$th->getMessage()}"];
        }
        return $return;
    }
}
