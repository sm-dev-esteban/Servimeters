<?php

/**
 * 
 * Historia de esta clase: Yo a pesar del tiempo que llevo programando
 * aún me considero un junior y lo chistoso es que cuando ven mi código me catalogan de una como senior o peor, en fin la ipocomdiacra.
 * 
 * Una vez estaba varado con algo y le presente mi código a alguien para que me ayudara
 * y me critico porque a según estaba usando mucho código espagueti aunque según yo usar heredoc no es espagueti
 * de hecho creo que es la solución para los que si lo hacen,
 * por el comentario sentí que debía crear una función sencilla que pudiera crear HTML y guala perdí como 10 min haciendo esta bobada.
 */

namespace Config;

# Experimental :/
class Html
{
    static function createTag(string $tagName = "div", array $attrs = [], string|array $innerHtml = "", bool $isPairTag = true): string
    {
        return self::formatTag(
            $tagName,
            $isPairTag,
            self::formatAttr($attrs),
            self::formatHtml($innerHtml)
        );
    }

    private static function formatHtml(string|array $innerHtml): string
    {
        return is_array($innerHtml) ? implode(PHP_EOL, $innerHtml) : $innerHtml;
    }

    private static function formatAttr(array $attrs): string
    {
        $renderValue = fn ($value) => self::render($value);
        $formattedAttrs = array_map(
            fn ($k, $v) => !is_numeric($k) && $v ? "{$k}=\"{$renderValue($v)}\"" : "{$k}",
            array_keys($attrs),
            array_values($attrs)
        );
        return implode(" ", $formattedAttrs);
    }

    private static function formatTag(string $tagName, bool $isPairTag, string $attrs, string $innerHtml): string
    {
        return $isPairTag
            ? "<{$tagName} {$attrs}>{$innerHtml}</{$tagName}>"
            : "<{$tagName} {$attrs} />";
    }

    private static function render($string)
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
    }
}
