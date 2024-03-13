<?php

$id = $_GET["report"] ?? null;

if (!$id) print <<<HTML
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 d-flex justify-content-center align-items-center">
                <div style="width: 500px; height: auto;">
                    <dotlottie-player src="https://assets-v2.lottiefiles.com/a/57355b06-1166-11ee-96d3-178b0eb98743/m8NEtAYYYJ.lottie" background="##FFF" speed="1" style="width: 100%; height: 100%" loop autoplay></dotlottie-player>
                </div>
            </div>
        </div>
    </div>
</section>
HTML;
else include __DIR__ . "/reportarHoras.view.php";
