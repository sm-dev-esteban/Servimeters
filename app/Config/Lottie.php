<?php

namespace Config;

final class Lottie
{
    private const ANIMATIONS = [
        "Questioning" => "https://assets-v2.lottiefiles.com/a/57355b06-1166-11ee-96d3-178b0eb98743/m8NEtAYYYJ.lottie",
        "Hellow" => "https://lottie.host/7d1fcb4e-13a4-4917-882b-aa444f99c855/HxfMtbFSGu.json"
    ];

    static function get($animationKey): ?string
    {
        $animation = self::ANIMATIONS[$animationKey] ?? null;

        switch (gettype($animation)) {
            case 'string':
                return $animation;
                break;
            case 'array':
                return $animation[array_rand($animation)];
                break;
            default:
                return null;
                break;
        }
    }
}
