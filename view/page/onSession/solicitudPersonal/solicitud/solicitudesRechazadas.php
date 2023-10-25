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
            <div class="card-body">
                <pre><?= json_encode($_SESSION, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?></pre>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
</section>