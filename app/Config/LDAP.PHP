<?php

namespace Config;

use Exception;
use System\Config\AppConfig;

class LDAP
{
    private $uri, $port, $ldap, $domain, $baseDn;
    private const LDAP_SETOPT = [
        "LDAP_OPT_PROTOCOL_VERSION" => 3,
        "LDAP_OPT_REFERRALS" => 0,
        "LDAP_OPT_SIZELIMIT" => 100
    ];

    public function __construct(?string $uri = null, ?int $port = null, array $options = [])
    {
        $this->uri = $uri ?: AppConfig::ACTIVE_DIRECTORY["URI"] ?? null;
        $this->port = $port ?: AppConfig::ACTIVE_DIRECTORY["PORT"] ?? null;

        $this->ldap = ldap_connect($this->uri, $this->port);
        if (!$this->ldap) throw new Exception("Error al conectar con el servidor ldap");

        $arraiUri = explode(".", $this->uri);
        $end = array_pop($arraiUri);
        $start = implode(".", $arraiUri);

        $this->domain = $start ?: AppConfig::ACTIVE_DIRECTORY["DOMAIN"] ?? null;
        $this->baseDn = $start && $end ? "DC={$start},DC={$end}" : AppConfig::ACTIVE_DIRECTORY["BASE"] ?? null;

        self::setOption(array_merge(self::LDAP_SETOPT, $options));
    }

    public function getBasedn()
    {
        return $this->baseDn;
    }

    public function setBasedn(string $base): self
    {
        $this->baseDn = $base;
        return $this;
    }

    public function setOption(array $options): self
    {
        foreach ($options as $const => $value) if (strpos($const, "LDAP_OPT_") !== false && defined($const)) ldap_set_option($this->ldap, constant($const), $value);
        return $this;
    }

    public function getdomain()
    {
        return $this->domain;
    }

    public function setdomain(string $domain): self
    {
        $this->domain = $domain;
        return $this;
    }

    private function getDn($user): string
    {
        return "{$this->domain}\\{$user}";
    }

    public function bind(string $user, string $pass): self
    {
        if (empty($user) || empty($pass)) throw new Exception("Usuario y contraseña son obligatorios");

        $dn = $this->getDn($user);
        $bind = @ldap_bind($this->ldap, $dn, $pass);

        if (!$bind) throw new Exception("Error al autenticar el usuario: " . ldap_error($this->ldap));

        return $this;
    }

    public function search(string $filter = "*", array $attr = ["*"]): array
    {
        $filter = $filter === "*" && in_array("*", $attr) ? "(objectClass=*)" : $filter;

        $result = @ldap_search($this->ldap, $this->baseDn, $filter, $attr);
        if (!$result) throw new Exception("Error al realizar la busqueda: " . ldap_error($this->ldap));

        $entries = ldap_get_entries($this->ldap, $result);

        if (isset($entries["count"])) unset($entries["count"]);

        return $entries;
    }
}
