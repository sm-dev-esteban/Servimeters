<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php /* --------------------------------------------------------------------- */ ?>
            <?php /* Gerente - Jefe */ ?>
            <?php if ($_SESSION["rol"] == "Gerente" || $_SESSION["rol"] == "Jefe") : ?>
                <div class="col-12 col-xl-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-calendar mr-1"></i>
                                Reporte anual de horas
                            </h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">line</span>
                                        <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">bar</span>
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">horizontalBar</span> -->
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">radar</span> -->
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">pie</span> -->
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">doughnut</span> -->
                                        <!-- <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">polarArea</span> -->
                                        <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">bubble</span>
                                        <span onclick="modifyChart('he_anual', $(this).html(), 'type')" class="dropdown-item">scatter</span>
                                    </div>
                                </div>
                                <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas class="chart" data-chart-ident="he_anual" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="row" data-chart-knob="he_anual">
                                <div class="col-5 col-xl-4 text-center">
                                    <input type="text" class="knob" data-knob="he_anual" data-readonly="true" data-width="60" data-height="60" data-fgColor="#28a745">
                                    <div data-chart-knob-title>% Horas Aprobadas</div>
                                </div>
                                <div class="col-5 col-xl-4 text-center">
                                    <input type="text" class="knob" data-knob="he_anual" data-readonly="true" data-width="60" data-height="60" data-fgColor="#dc3545">
                                    <div data-chart-knob-title>% Horas Rechazadas</div>
                                </div>
                                <div class="col-5 col-xl-4 text-center">
                                    <input type="text" class="knob" data-knob="he_anual" data-readonly="true" data-width="60" data-height="60" data-fgColor="#17a2b8">
                                    <div data-chart-knob-title>% Horas en Proceso</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-clock mr-1"></i>
                                Horas reportadas en centros de costos
                            </h3>
                            <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52" aria-expanded="false">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">line</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">bar</span> -->
                                        <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">horizontalBar</span>
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">radar</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">pie</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">doughnut</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">polarArea</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">bubble</span> -->
                                        <!-- <span onclick="modifyChart('he_ceco', $(this).html(), 'type')" class="dropdown-item">scatter</span> -->
                                    </div>
                                </div>
                                <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas class="chart" data-chart-ident="he_ceco" data-chart-type="horizontalBar" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="row" data-chart-knob="he_ceco"></div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php /* --------------------------------------------------------------------- */ ?>
            <?php /* Gerente */ ?>
            <?php if ($_SESSION["rol"] == "Gerente") : ?>
            <?php endif ?>
            <?php /* --------------------------------------------------------------------- */ ?>
            <?php /* Jefe */ ?>
            <?php if ($_SESSION["rol"] == "Jefe") : ?>
            <?php endif ?>
            <?php /* --------------------------------------------------------------------- */ ?>
        </div>
    </div>
</section>