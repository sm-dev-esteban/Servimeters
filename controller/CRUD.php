<?php

namespace Controller;

class CRUD
{
    static function view(String $table, array $columns): String
    {
        $iden = date("YmdHis");
        $form = self::viewForm($columns, $iden);
        $table = self::viewTable($table, $columns, $iden);
        return <<<HTML
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-one-tab-{$iden}" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="custom-tabs-one-register-{$iden}-tab-{$iden}" data-toggle="pill" href="#custom-tabs-one-register-{$iden}" role="tab" aria-controls="custom-tabs-one-register-{$iden}" aria-selected="true">Registro</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="custom-tabs-one-data-tab-{$iden}" data-toggle="pill" href="#custom-tabs-one-data" role="tab" aria-controls="custom-tabs-one-data" aria-selected="false">Datos</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-one-tabContent-{$iden}">
                        <div class="tab-pane fade show active" id="custom-tabs-one-register-{$iden}" role="tabpanel"
                            aria-labelledby="custom-tabs-one-register-{$iden}-tab-{$iden}">
                            <div>
                                {$form}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-one-data" role="tabpanel" aria-labelledby="custom-tabs-one-data-tab-{$iden}">
                            <div class="table-responsive">
                                {$table}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
    }

    static function viewForm(array $tagConfig, $i): String
    {
        $string = "";
        foreach ($tagConfig as $data) {
            $db = $data['db'] ?? "";
            $tag = $data['tag'] ?? [];

            $tag = self::createTag($tag, $db);

            $string .= <<<HTML
                <div class="mb-3">
                    {$tag}
                </div>
            HTML;
        }
        return <<<HTML
            <form data-action="{$i}">
                {$string}
                <div class="mb-3">
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        HTML;
    }
    static function viewTable(String $table, array $tagConfig, $i): String
    {

        return <<<HTML
            <table class="table"></table>
        HTML;
    }

    static function createTag($tagConfig, $name = ""): String
    {
        $string = "";
        foreach ($tagConfig as $key => $value) {
            $innerText = $value["innerText"] ?? "";
            $innerJoin = $value["innerJoin"] ?? [];
            $label = $value["label"] ?? false;
            unset(
                $value["innerText"],
                $value["innerJoin"],
                $value["label"],
                $value["name"]
            );

            if ($label) $string .= self::createTag(["label" => $label]);

            $attrs = implode(" ", array_map(function ($k, $v) {
                return $k . '=' . (is_array($v) ? json_encode($v) : '"' . $v . '"');
            }, array_keys($value), array_values($value)));

            switch (strtoupper(str_replace(" ", "", $key))) {
                case 'LABEL':
                    $string .= <<<HTML
                        <label {$attrs}>{$innerText}</label>
                    HTML;
                    break;
                case 'INPUT':
                    $string .= <<<HTML
                        <input name="data[{$name}]" {$attrs}>
                    HTML;
                    break;
                case 'SELECT':
                    $options = self::optionsBySelect($innerJoin);
                    $string .= <<<HTML
                        <select name="data[{$name}]" {$attrs}>
                            {$options}
                        </select>
                    HTML;
                    break;
                default:
                    $string .= <<<HTML
                        <input name="data[{$name}]" type="text">
                    HTML;
                    break;
            }
        }
        return $string;
    }
    static function optionsBySelect($inner): String
    {
        include FOLDER_SIDE . "/conn.php";

        $options = "";

        $table = $inner["table"] ?? "";
        $id = $inner["id"] ?? "";
        $value = $inner["value"] ?? "";
        $filter = $inner["filter"] ?? "1 = 1";

        $columns = implode(", ", array_filter([$id, $value], function ($x) {
            return !empty($x);
        }));

        $result = $db->executeQuery(<<<SQL
            select {$columns} from {$table} where {$filter}
        SQL);
        $error = $db->getError($result);

        if (!$error) foreach ($result as $data) {
            $i = $data[$id] ?? "";
            $v = $data[$value] ?? "";
            $options .= <<<HTML
                <option value="{$i}">{$v}</option>
            HTML;
        }

        return $options;
    }
}
