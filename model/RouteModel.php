<?php

namespace Model;

class RouteModel
{
    public function getURI()
    {
        $REQUEST_URI = $_GET["route"] ?? "default/";
        return  "/" . $REQUEST_URI . (substr($REQUEST_URI, -1) === "/" ? "index" : "");
    }

    public function template_for_new_views($type)
    {
        $ඞ = FOLDER_SIDE;
        $date = date("Y-m-d H:i:s");
        return [
            "ONSESSION" => [
                "FRONTEND" => <<<'HTML'
                <?php

                use Model\RouteModel;

                $routeM = new RouteModel;
                ?>

                <section class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>(╹ڡ╹ )</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item active"><?= substr($routeM->getURI(), 1) ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="content">
                    <div class="container-fluid">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">(～﹃～)~zZ</h3>
                            </div>
                            <div class="card-body"></div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                </section>
                HTML,
                "BACKEND" => <<<HTML
                <?php+
                /*-- {$date} --*/

                include_once "{$ඞ}/vendor/autoload.php";
                include "{$ඞ}/Config.php";
                include "{$ඞ}/conn.php";

                date_default_timezone_set(TIMEZONE);
                
                HTML,
                "CSS" => <<<CSS
                /*-- {$date} --*/
                CSS,
                "JS" => <<<JS
                /*-- {$date} --*/

                $(document).ready(async () => {
                    console.log(`pozole`)
                })
                JS
            ],
            "OFFSESSION" => [
                "FRONTEND" => <<<HTML
                <!-- {$date} -->
                HTML,
                "BACKEND" => <<<HTML
                <!-- {$date} -->
                HTML,
                "CSS" => <<<CSS
                /*-- {$date} --*/
                CSS,
                "JS" => <<<JS
                /*-- {$date} --*/
                JS
            ]
        ][strtoupper(TEMPLATE_VIEW)][strtoupper($type)] ?? $date;
    }
}
