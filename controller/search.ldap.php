<?php

$search = isset($_POST["search"]) ? $_POST["search"] : "";

$user = isset($_POST["user"]) ? $_POST["user"] : "esteban.serna";
$pass = isset($_POST["pass"]) ? $_POST["pass"] : "Es123456*";

$ldap_get_entries = [];

$dn = 'dc=' . getenv("USERDOMAIN") . ', dc=COM';

$attributes = ["ou", "name", "mail", "samaccountname"];

define("FILTER", $search);
$filter = "(|" . implode("", array_map(function ($x) {
    return "({$x}=" . FILTER . "*)";
}, $attributes)) . ")";


$ldap = ldap_connect(getenv("USERDNSDOMAIN"));
ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
$ldap_bind = ldap_bind($ldap, getenv("USERDOMAIN") . "\\{$user}", $pass);

$ldap_search = ldap_search($ldap, $dn, $filter, $attributes);
$ldap_get_entries = ldap_get_entries($ldap, $ldap_search);

ldap_close($ldap);


echo json_encode($ldap_get_entries, JSON_UNESCAPED_UNICODE);
