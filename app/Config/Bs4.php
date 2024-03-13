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
    static function Button(string $text, array $customAttrs = []): string
    {
        $attrs = [
            "class" => "btn btn-primary",
            "type" => "button"
        ];

        $attrs = [...$attrs, ...$customAttrs];

        return self::createTag(
            tagName: "button",
            attrs: $attrs,
            innerHtml: $text
        );
    }

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

    static function Accordion(string $dataParent, array $info, array $customAttrs = []): string
    {
        $attrs = [
            "card" => [
                ...[
                    "class" => "card card-primary card-outline"
                ], ...$customAttrs["card"] ?: []
            ],
            "card-header" => [...[
                "class" => "card-header"
            ], ...$customAttrs["card-header"] ?: []],
            "card-title" => [
                ...[
                    "class" => "card-title w-100"
                ], ...$customAttrs["card-title"] ?: []
            ],
            "card-body" => [
                ...[
                    "class" => "card-body",
                ], ...$customAttrs["card-body"] ?: []
            ]
        ];

        $response = [];

        foreach ($info as $i => $data) {
            [
                "title" => $title,
                "content" => $content,
                "collapse" => $collapse,
            ] = $data;

            $uid = "{$i}_" . uniqid();

            $response[] = self::createTag(
                attrs: $attrs["card"],
                innerHtml: [
                    self::createTag(
                        tagName: "a",
                        attrs: [
                            "class" => "d-block w-100",
                            "data-toggle" => "collapse",
                            "href" => "#collapse_{$uid}",
                        ],
                        innerHtml: self::createTag(
                            attrs: $attrs["card-header"],
                            innerHtml: self::createTag(
                                tagName: "h4",
                                attrs: $attrs["card-title"],
                                innerHtml: $title
                            )
                        )
                    ),
                    self::createTag(
                        attrs: [
                            "id" => "collapse_{$uid}",
                            "class" => "collapse" . (!empty($collapse) ? " show" : ""),
                            "data-parent" => $dataParent
                        ],
                        innerHtml: self::createTag(
                            attrs: $attrs["card-body"],
                            innerHtml: $content
                        )
                    )
                ]
            );
        }

        return implode(PHP_EOL, $response);
    }

    private static function removeEmptyValues(array $array)
    {
        return array_filter($array, fn ($val) => !empty($val));
    }
}
