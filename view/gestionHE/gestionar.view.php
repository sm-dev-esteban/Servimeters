<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="overlay d-none">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3 col-xl-6">
                        <button class="btn btn-success btn-block" id="aprobar"><i class="fa fa-check"></i> Aprobar</button>
                    </div>
                    <div class="col-12 mb-3 col-xl-6">
                        <button class="btn btn-danger btn-block" id="rechazar"><i class="fa fa-trash"></i> Rechazar</button>
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-12 mb-3 col-xl-6">
                        <button class="btn btn-success btn-block" id="STodo"><i class="fa fa-check-circle"></i> Seleccionar Todo</button>
                    </div>
                    <div class="col-12 mb-3 col-xl-6">
                        <button class="btn btn-primary btn-block" id="DTodo"><i class="fa fa-check-circle"></i> Deseleccionar Todo</button>
                    </div>
                    <div class="col-12 mb-3 col-xl-12">
                        <!-- <button type="button" class="btn btn-default position-relative m-1">
                            APROBACION_JEFE
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-olive">
                                ?
                            </span>
                        </button>
                        <button type="button" class="btn btn-default position-relative m-1">
                            APROBACION_GERENTE
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-teal">
                                ?
                            </span>
                        </button>
                        <button type="button" class="btn btn-default position-relative m-1">
                            APROBACION_RH
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-lime">
                                ?
                            </span>
                        </button>
                        <button type="button" class="btn btn-default position-relative m-1">
                            APROBACION_CONTABLE
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-orange">
                                ?
                            </span>
                        </button> -->
                    </div>
                    <div class="col-12 mb-3">
                        <div class="table-responsive">
                            <table class="table" id="listAprov">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Documento</th>
                                        <th>Año</th>
                                        <th>Mes</th>
                                        <th>Colaborador</th>
                                        <th>Estado</th>
                                        <th>Clase</th>
                                        <th>CeCo</th>
                                        <th>Descuento</th>
                                        <th>Extras Diurn Ordinaria</th>
                                        <th>Extras Noct Ordinaria</th>
                                        <th>Extras Diurn Fest Domin</th>
                                        <th>Extras Noct Fest Domin</th>
                                        <th>Recargo Nocturno</th>
                                        <th>Recargo Festivo Diurno</th>
                                        <th>Recargo Festivo Noctur</th>
                                        <th>Recargo Ord Fest Noct</th>
                                        <th>Total</th>
                                        <th>Ver detalle</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>