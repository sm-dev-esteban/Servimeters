<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">
                    <i class="fas fa-calendar mr-1"></i>
                    Reporte anual de horas
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas class="chart" data-chart="he_anual" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <div class="card-footer bg-transparent">
                <div class="row">
                    <div class="col-4 text-center">
                        <input type="text" class="knob" data-knob="he_anual" data-readonly="true" data-width="60" data-height="60" data-fgColor="#28a745">
                        <div>Porcentaje de Horas Aprobadas</div>
                    </div>
                    <div class="col-4 text-center">
                        <input type="text" class="knob" data-knob="he_anual" data-readonly="true" data-width="60" data-height="60" data-fgColor="#dc3545">
                        <div>Porcentaje de Horas Rechazadas</div>
                    </div>
                    <div class="col-4 text-center">
                        <input type="text" class="knob" data-knob="he_anual" data-readonly="true" data-width="60" data-height="60" data-fgColor="#dc3545">
                        <div>Porcentaje de Horas en Proceso</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $.getScript("../assets/js/home.js");
</script>