<?php

use Config\Route;
use Config\ImageProcessor;
use System\Config\AppConfig;

?>

<body class="hold-transition login-page" data-router style="--image: url('<?= Route::href("Img/pic10.webp") ?>')">
    <div class="login-box">
        <div class="card card-outline card-primary glass">
            <div class="card-header text-center">
                <a target="_blank" href="<?= AppConfig::COMPANY["HOME_PAGE"] ?>" class="h1">
                    <img src="<?= ImageProcessor::correctImageURL(AppConfig::COMPANY["LOGO_HORIZONTAL"]) ?>" alt="Servimeters" class="img-fluid">
                </a>
            </div>
            <div class="card-body">
                <!-- <p class="login-box-msg">Digite las credenciales de su equipo</p> -->

                <form id="signIn">
                    <div class="input-group mb-3">
                        <input type="text" name="data[user]" class="form-control glass" placeholder="Nombre de usuario" required>
                        <div class="input-group-append">
                            <div class="input-group-text glass">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="data[pass]" class="form-control glass" placeholder="Contraseña" required>
                        <div class="input-group-append">
                            <div class="input-group-text glass">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>