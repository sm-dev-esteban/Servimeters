<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    Solicitudes Rechazadas
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" data-ssp="listSolicitudRechazada">
                        <thead class="shadow">
                            <tr>
                                <th>N° Requisición</th>
                                <th>Proceso</th>
                                <th>Cargo</th>
                                <th>Modificar Solicitud</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>N° Requisición</th>
                                <th>Proceso</th>
                                <th>Cargo</th>
                                <th>Modificar Solicitud</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<JSON-loadScript style="display: none;">
    <?= json_encode([
        "../assets/js/sPersonal.js"
    ], JSON_UNESCAPED_UNICODE) ?>
</JSON-loadScript>