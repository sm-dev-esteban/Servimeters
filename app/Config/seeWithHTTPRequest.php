<?php

namespace Config;

use Config\Route;
use System\Config\AppConfig;


class SeeWithHTTPRequest
{
    static function httpView(?string $uri)
    {
        $route = new Route();

        $url = parse_url($uri);

        self::formatGets($url);

        $route->setPage($url["path"] ?? "/");
        $route->view(!AppConfig::PRODUCTION);
    }

    private static function formatGets($urlInfo): void
    {
        if (isset($urlInfo["query"]))
            parse_str(urldecode($urlInfo["query"]) . "&route={$urlInfo["path"]}", $_GET);
        else if (isset($urlInfo["path"]))
            parse_str("route={$urlInfo["path"]}", $_GET);
    }
}
