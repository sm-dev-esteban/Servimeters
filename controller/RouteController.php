<?php

namespace Controller;

use Exception;
use Model\DB;
use Model\RouteModel;

class RouteController
{
    private $page, $routeModel;

    /**
     * 
     */
    public function __construct(private $folder_views, private $array_folder_error = [])
    {
        $this->array_folder_error = array_merge([
            "E404" => false,
            "E500" => false
        ], $array_folder_error);
        $this->page = false;

        $this->routeModel = new RouteModel();
    }

    /**
     * 
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * 
     */
    public function setPage($newPage)
    {
        $this->page = $newPage;
    }

    /**
     * 
     */
    public function showPage($createView = false)
    {

        try {
            $route = $this->routeModel->getURI();

            $ext = explode(".", !$this->page ? $route : $this->page);
            $this->page = str_replace("//", "/", $this->folder_views . $ext[0] . "." . ($ext[1] ?? "php"));

            if ($createView === true) self::createView();

            if (file_exists($this->page)) {
                $folder = self::string_slice($this->page, "/", 0, -1);
                # connect to database
                include FOLDER_SIDE . "/conn.php";

                # css
                // foreach (self::folder_to_server(glob($folder . "/*.css")) as $key => $value) echo '<link rel="stylesheet" href="' . $value . '">';
                // echo '<LOAD-CSS style="display: none !important">', json_encode(self::folder_to_server(glob($folder . "/*.css")), JSON_UNESCAPED_UNICODE), "</LOAD-CSS>";
                echo implode("\n", array_map(function ($css) {
                    return "<link rel=\"stylesheet\" href=\"{$css}\">";
                }, self::folder_to_server(glob($folder . "/*.css"))));

                # content
                include $this->page;

                # scripts
                // foreach (self::folder_to_server(glob($folder . "/*.js")) as $key => $value) echo '<script src="' . $value . '"></script>';
                echo '<LOAD-SCRIPT style="display: none !important">', json_encode(self::folder_to_server(glob($folder . "/*.js")), JSON_UNESCAPED_UNICODE), "</LOAD-SCRIPT>";
            } else if ($this->array_folder_error["E404"] && file_exists($this->array_folder_error["E404"]))
                include $this->array_folder_error["E404"];
            else {
                $SERVER_SIDE = SERVER_SIDE;
                echo <<<HTML
                <div class="text-center">
                    <h1 class="display-1 fw-bold">404</h1>
                    <p class="fs-3"> <span class="text-warning">Opps!</span> Page not found.</p>
                    <p class="lead">{$route}</p>
                    <a href="{$SERVER_SIDE}" class="btn btn-primary">Regresar al Inicio</a>
                </div>
                HTML;
            }
        } catch (Exception $th) { // no se como puede llegar hasta aqui,pero mejor prevenir
            if ($this->array_folder_error["E500"] && file_exists($this->array_folder_error["E500"]))
                include $this->array_folder_error["E500"];
            else {
                $SERVER_SIDE = SERVER_SIDE;
                echo <<<HTML
                <div class="text-center">
                    <h1 class="display-1 fw-bold">500</h1>
                    <p class="fs-3"> <span class="text-danger">Error</p>
                    <p class="lead">{$th->getMessage()}</p>
                    <a href="{$SERVER_SIDE}" class="btn btn-primary">Regresar al Inicio</a>
                </div>
                HTML;
            }
        }
    }

    /**
     * 
     */
    private function folder_to_server(array|String $string)
    {
        return [
            "ARRAY" => (is_array($string) ? array_map(function ($x) {
                return str_replace(FOLDER_SIDE, SERVER_SIDE, $x);
            }, $string) : ""),
            "STRING" => str_replace(FOLDER_SIDE, SERVER_SIDE, $string)
        ][strtoupper(gettype($string))] ?? $string;
    }

    private function string_slice(String $string, array|String $separator, Int $offset, Int $length)
    {
        return implode($separator, array_slice(explode($separator, $string), $offset, $length));
    }

    /**
     * 
     */
    public function createView(): Bool
    {
        try {
            $page = self::getPage();
            $folder = self::string_slice($page, "/", 0, -1);

            $files = [
                "FRONTEND" => $page,
                "BACKEND" => $folder . "/backend.php",
                "CSS" => $folder . "/style.css",
                "JS" => $folder . "/frontend.js"
            ];

            # creo la carpeta
            if (!file_exists($folder)) mkdir($folder, 0777, true);

            # creo los archivos
            foreach ($files as $key => $filename) if (!file_exists($filename)) {
                echo <<<HTML
                <pre class="m-0 p-0">
                    new file created: {$filename}
                </pre>
                HTML;
                $openString = fopen($filename, "w");
                fwrite($openString, $this->routeModel->template_for_new_views($key));
                fclose($openString);
            }

            #retorno true si todo nice
            return true;
        } catch (Exception $th) {
            #retorno false y que se jodan las validaciones 😎
            return false;
        }
    }
}
