<div class="login-box">
    <div class="card card-outline card-primary glass">
        <div class="card-header text-center">
            <a href="https://www.servimeters.com/" class="h1"><img src="<?= SERVER_SIDE ?>/Img/SM HORIZONTAL.png" alt="Servimeters" class="img-fluid"></a>
        </div>
        <div class="card-body">
            <!-- <p class="login-box-msg">Digite las credenciales de su equipo</p> -->

            <form id="signIn">
                <div class="input-group mb-3">
                    <input type="text" name="user" class="form-control glass" placeholder="Nombre de usuario" required>
                    <div class="input-group-append">
                        <div class="input-group-text glass">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="pass" class="form-control glass" placeholder="Contraseña" required>
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

<!-- Bootstrap 4 -->
<script src="<?= SERVER_SIDE ?>/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= SERVER_SIDE ?>/AdminLTE/dist/js/adminlte.min.js"></script>