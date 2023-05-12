<?php

class LDAP{

    private const DOMINIO = 'SERVIMETERSSA.COM';
    private const DN = 'dc=SERVIMETERSSA,dc=COM';
    private const PUERTO = 389;
    private $connection;
    private $result;


    public function connectAD($user, $pass){
        $this->connection = ldap_connect(self::DOMINIO, self::PUERTO);
        ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);

        $confirmacionUser = ldap_bind($this->connection, "SERVIMETERSSA\\$user", $pass);

        if ($confirmacionUser) {

            $filter = "(|(SAMAccountName=" . $user . "))";
            $searchUser = ldap_search($this->connection, self::DN, $filter);
            $resultSearch = ldap_get_entries($this->connection, $searchUser);
            $this->result = $resultSearch;

        } else {
            $this->result = "";
        }

        ldap_close($this->connection);
        return $this->result;

    }
}