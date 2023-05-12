<?php
// session_start();
if (!isset($_SESSION["isAdmin"]) || (strcasecmp($_SESSION["isAdmin"], 'Si') !== 0)) {
    require_once "../../config/LoadConfig.config.php";
    $config = LoadConfig::getConfig();
    header('Location:' . $config['URL_SITE'] . 'index.php');
}

?>

<section id="four" class="content">
    <div class="container">
        <header>
            <h3>Agregar Aprobadores</h3>
        </header>
        <form action="#" id="formAprob">
            <div class="row">
                <section class="col-4 col-md-3 col-sm-1">
                    <label for="name">Nombre</label>
                    <input type="text" name="name" style="color: black !important;" required />
                </section>
                <section class="col-4 col-md-6 col-sm-10">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" style="color: black !important;" required />
                </section>
                <section class="col-4 col-md-3 col-sm-1">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo">
                        <option value="NA">NA</option>
                        <option value="Jefe">Jefe</option>
                        <option value="Gerente">Gerente</option>
                    </select>
                </section>
                <section class="col-4 col-md-3 col-sm-1">
                    <label for="tipo">Gestiona</label>
                    <select name="gestiona" id="gestiona">
                        <option value="NA">NA</option>
                        <option value="Contable">Contable</option>
                        <option value="RH">RH</option>
                    </select>
                </section>
                <section class="col-4 col-md-3 col-sm-1">
                    <label for="tipo">Es Administrador</label>
                    <select name="isadmin" id="isadmin">
                        <option value="No">No</option>
                        <option value="Si">Si</option>
                    </select>
                </section>
                <section class="col-12 col-md-8 col-sm-12" id="butonSend">
                    <footer class="major">
                        <ul class="actions special">
                            <li><button type="submit" id="sendDataAprob" class="btn btn-primary fas fa-check-circle fi">Guardar</button></li>
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
            <h3 style="color: white;">Administracion Aprobadores</h3>
        </header>

        <section class="col-12 col-md-4 col-sm-12">
            <table class="tableAdmin">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Tipo</th>
                        <th>Gestiona</th>
                        <th>Es Administrador</th>
                        <th>Guardar</th>
                    </tr>
                </thead>
                <tbody id="aprobadores">
                    <!-- Llenar datos con iteracion -->
                </tbody>
            </table>
        </section>
    </div>
</section>