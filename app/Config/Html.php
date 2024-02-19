<?php

/**
 * Historia de esta clase: Yo a pesar del tiempo que llevo programando aún me considero un junior
 * y lo chistoso es que cuando compañeros ven mi código me catalogan de una como senior por mi forma de programar,
 * bueno el caso es que una vez estaba varado con algo y cuando presente mi código para que me ayudaran me criticaron
 * porque a según estaba usando código espagueti aunque según yo usar heredoc no es espagueti
 * de hecho creo que es la solución para los que si lo hacen igual,
 * pero igual por el comentario sentí que debía crear una función sencilla que pudiera crear HTML y guala.
 */

namespace Config;

# Experimental :/
class Html
{
    // const TAG_TEMPLATES = [
    //     "input" => [
    //         "attrs" => [
    //             "class" => "form-control",
    //             "type" => "text"
    //         ],
    //         "isPairTag" => false
    //     ]
    // ];

    // static function createTag(string $tagName, array $attrs = [], bool $isPairTag = true): string
    // {
    //     $html = "";
    //     $attr = "";

    //     if ($isPairTag && isset($attrs["html"]) && is_array($attrs["html"])) {
    //         foreach ($attrs["html"] as $newTag)
    //             if (isset($newTag["tagName"])) {

    //                 if (self::TAG_TEMPLATES[$newTag["tagName"]]) $newTag = array_merge(self::TAG_TEMPLATES[$newTag["tagName"]], $newTag);

    //                 $innerHtml = self::createTag(
    //                     $newTag["tagName"],
    //                     $newTag["attrs"] ?? [],
    //                     $newTag["isPairTag"] ?? true
    //                 );

    //                 $html .= $innerHtml;
    //             }
    //     } else $html = htmlspecialchars($attrs["html"] ?? "", ENT_QUOTES);

    //     unset($attrs["html"]);

    //     $attr = implode(" ", array_map(function ($key, $value) {
    //         $value = is_array($value) ? json_encode($value) : $value;

    //         return "{$key}=\"{$value}\"";
    //     }, array_keys($attrs), array_values($attrs)));

    //     return "<{$tagName} {$attr}>" . ($isPairTag ? "{$html}</{$tagName}>" : "");
    // }

    static function createTag(string $tagName = "div", array $attrs = [], string|array $innerHtml = "", bool $isPairTag = true): string
    {
        return self::formatTag(
            tagName: $tagName,
            isPairTag: $isPairTag,
            attrs: self::formatAttr(
                attrs: $attrs
            ),
            innerHtml: self::formatHtml(
                innerHtml: $innerHtml
            )
        );
    }

    private static function formatHtml(string|array $innerHtml): string
    {
        return is_array($innerHtml) ? implode("\n", $innerHtml) : $innerHtml;
    }

    private static function formatAttr(array $attrs): string
    {
        $renderValue = fn ($value) => json_encode($value, JSON_UNESCAPED_UNICODE);
        return implode(" ", array_map(fn ($key, $value) => "{$key}={$renderValue($value)}", array_keys($attrs), array_values($attrs)));
    }

    private static function formatTag(string $tagName, string $attrs, string $innerHtml, $isPairTag = true)
    {
        return $isPairTag ? "<{$tagName} {$attrs}>{$innerHtml}</{$tagName}>" : "<{$tagName} {$attrs} />";
    }
}
