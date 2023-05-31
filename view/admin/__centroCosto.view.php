<?php

if (!isset($_SESSION["isAdmin"]) || (strcasecmp($_SESSION["isAdmin"], 'Si') !== 0)) {
    require_once "../../config/LoadConfig.config.php";
    $config = LoadConfig::getConfig();
    header('Location:' . $config['URL_SITE'] . 'index.php');
}

?>

<section id="four" class="content">
    <div class="container">
        <header>
            <h3>Agregar Centro de Costo</h3>
        </header>
        <form action="#" id="formCeco">
            <div class="row">
                <section class="col-6 col-md-6 col-sm-12">
                    <label for="title">Nombre</label>
                    <input type="text" name="title" placeholder="Ingrese el titulo del centro de costo" style="color: black !important; width: 50%; margin: auto;" required />
                </section>
                <section class="col-6 col-md-6 col-sm-12">
                    <label for="title">Clase</label>
                    <select name="clase" id="clase" style="color: black !important; width: 50%; margin: auto;" required>
                        <!-- Llenar dinamicamente -->
                    </select>
                </section>
                <section class="col-12 col-md-8 col-sm-12" id="butonSend">
                    <footer class="major">
                        <ul class="actions special">
                            <li><button type="submit" id="sendData" class="btn btn-primary fas fa-check-circle fi">Guardar</button></li>
                        </ul>
                    </footer>
                </section>
            </div>
        </form>
    </div>
</section>

<section id="five" class="wrapper style2 special fade">
    <div class="container">
        <header>
            <h3 style="color: white;">Administracion Centro de Costo</h3>
        </header>

        <section class="col-12 col-md-4 col-sm-12">
            <table class="tableAdmin">
                <thead>
                    <tr>
                        <th>Titulo</th>
                        <th>Clase</th>
                        <th>Guardar</th>
                    </tr>
                </thead>
                <tbody id="ceco">
                    <!-- Llenar datos con iteracion -->
                </tbody>
            </table>
        </section>
    </div>
</section>