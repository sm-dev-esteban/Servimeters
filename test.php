<?php

use Model\LDAP;

include_once __DIR__ . "/vendor/autoload.php";

print <<<HTML
    <head>
        <title>LDAP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </head>
HTML;

$LDAP = new LDAP();

print '<div class="container">';
//*---------------------------------------------------------------------------*//
$user = "esteban.serna";
$pass = "Es123456*";

print <<<HTML
    <h2>Default</h2>
    <ul>
        <li><b>uri: </b>{$LDAP->uri}</li>
        <li><b>dn: </b>{$LDAP->dn}{$user}</li>
        <li><b>base: </b>{$LDAP->base}</li>
        <li><b>port: </b>{$LDAP->port}</li>
    </ul>
HTML;

$result1 = $LDAP->connect($user, $pass);
$json1 = json_encode($result1, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

print <<<HTML
    <h2>test result</h2>
    <pre>{$json1}</pre>
HTML;
//*---------------------------------------------------------------------------*//
$user = "";
$pass = "password";

$LDAP->uri = "ldap.forumsys.com";
$LDAP->dn = "cn=read-only-admin,dc=example,dc=com";
$LDAP->base = "cn=read-only-admin,dc=example,dc=com";
$LDAP->port = 389;

print <<<HTML
    <h2>From test</h2>
    <ul>
        <li><b>uri: </b>{$LDAP->uri}</li>
        <li><b>dn: </b>{$LDAP->dn}{$user}</li>
        <li><b>base: </b>{$LDAP->base}</li>
        <li><b>port: </b>{$LDAP->port}</li>
    </ul>
HTML;

$result2 = $LDAP->connect($user, $pass, "*a*");
$json2 = json_encode($result2, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

print <<<HTML
    <h2>test result</h2>
    <pre>{$json2}</pre>
HTML;
//*---------------------------------------------------------------------------*//
print '</div>';
