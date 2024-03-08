<?php

/**
 * 
 * Clase de prueba realmente creo que no la voy a usar
 * 
 * Bootstrap: 4.6
 * AdminLte: 3.1
 */

namespace Config;

class Bs4 extends Html
{
    // static function Button()
    // {
    // }

    static function Card($header = null, $body = null, $footer = null): string
    {
        $card_header = $header ? self::createTag(attrs: ["class" => "card-header"], innerHtml: $header) : null;
        $card_body = $body ? self::createTag(attrs: ["class" => "card-body"], innerHtml: $body) : null;
        $card_footer = $footer ? self::createTag(attrs: ["class" => "card-footer"], innerHtml: $footer) : null;

        return self::createTag(
            attrs: ["class" => "card"],
            innerHtml: self::removeEmptyValues(array: [
                $card_header,
                $card_body,
                $card_footer
            ])
        );
    }

    static function Modal($id, $header = null, $body = null, $footer = null): string
    {
        $modal_header = $header ? self::createTag(attrs: ["class" => "modal-header"], innerHtml: $header) : null;
        $modal_body = $body ? self::createTag(attrs: ["class" => "modal-body"], innerHtml: $body) : null;
        $modal_footer = $footer ? self::createTag(attrs: ["class" => "modal-footer justify-content-between"], innerHtml: $footer) : null;

        $modal_dialog = self::createTag(attrs: ["class" => "modal-content"], innerHtml: self::removeEmptyValues(array: [
            $modal_header,
            $modal_body,
            $modal_footer
        ]));

        $modal_content = self::createTag(attrs: ["class" => "modal-dialog"], innerHtml: $modal_dialog);

        return self::createTag(
            attrs: ["class" => "modal fade", "id" => $id],
            innerHtml: $modal_content
        );
    }

    private static function removeEmptyValues(array $array)
    {
        return array_filter($array, fn ($val) => !empty($val));
    }

    private static function render($html)
    {
        return htmlspecialchars($html);
    }
}
