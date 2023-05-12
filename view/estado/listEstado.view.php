<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="listHE">
                        <thead class="shadow">
                            <tr>
                                <th>#</th>
                                <th>Documento</th>
                                <th>Centro Costo</th>
                                <th>Clase</th>
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Aprobador</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Documento</th>
                                <th>Centro Costo</th>
                                <th>Clase</th>
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Aprobador</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="viewDetail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 id="detail-title">Reporte #x</h2>
                <div class="row" id="detail-content">
                    <div class="col-6">
                        <p><b>Documento: </b>##########</p>
                        <p><b>Usuario: </b>##########</p>
                        <p><b>Correo: </b>##########</p>
                    </div>
                    <div class="col-6">
                        <p><b>Centro de costo: </b>text</p>
                        <p><b>Proyecto asociado: </b>text</p>
                        <p><b>Codigo: </b>text</p>
                    </div>
                </div>
                <table id="detail-content">
                    <thead>
                        <tr>
                            <th colspan="9">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th colspan="9">Actividad</th>
                        </tr>
                        <tr>
                            <td colspan="9">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Saepe aut consectetur ipsa rerum facere harum temporibus illum quasi. Nemo veritatis delectus similique asperiores labore cupiditate dolores quod vero reiciendis necessitatibus?</td>
                        </tr>
                        <tr>
                            <th>Permisos Descuentos</th>
                            <th>Extras</th>
                            <th>Extras</th>
                            <th>Extras</th>
                            <th>Extras</th>
                            <th>Diurnas</th>
                            <th>Diurnas</th>
                            <th>Diurnas</th>
                            <th>Diurnas</th>
                        </tr>
                        <tr>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                            <td>#</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i></button>
                <button type="button" class="btn btn-primary" data-action="print"><i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>
</div>