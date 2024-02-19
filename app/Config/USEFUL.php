<?php

namespace Config;

use Closure;

class USEFUL
{
    public Closure $options;

    public function __construct()
    {
        $this->options = fn (array $array, string $value, string $text, mixed $selected = null): string => implode("\n", array_map(function ($data) use ($value, $text, $selected) {
            $value = $data[$value] ?? "";
            $text = $data[$text] ?? "";
            $selected = $selected == $value || $selected == $text ? "selected" : "";

            return "<option value=\"{$value}\" {$selected}>{$text}</option>";
        }, $array));
    }
}
