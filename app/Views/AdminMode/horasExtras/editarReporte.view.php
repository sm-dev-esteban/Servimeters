<?php

$id = $_GET["report"] ?? null;

if (!$id) print <<<HTML
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <div style="width: 500px; height: auto;">
                    <dotlottie-player src="https://lottie.host/7d1fcb4e-13a4-4917-882b-aa444f99c855/HxfMtbFSGu.json" background="##FFF" speed="1" style="width: 100%; height: 100%" loop autoplay></dotlottie-player>
                </div>
            </div>
        </div>
    </div>
</section>
HTML;
else include __DIR__ . "/reportarHoras.view.php";
