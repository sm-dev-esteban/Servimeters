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
            <div class="overlay">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <h2 id="detail-title">Reporte #x</h2>
                    <div class="row" id="detail-content">
                        <div class="col-6">
                            <p data-report="document"><b>Documento: </b>##########</p>
                            <p data-report="user"><b>Usuario: </b>##########</p>
                            <p data-report="mail"><b>Correo: </b>##########</p>
                        </div>
                        <div class="col-6">
                            <p data-report="ceco"><b>Centro de costo: </b>text</p>
                            <p data-report="proyect"><b>Proyecto asociado: </b>text</p>
                            <p data-report="code"><b>Codigo: </b>text</p>
                        </div>
                        <div class="col-12">
                            <p data-report="aprobador"><b>Aprobador: </b>##########</p>
                        </div>
                    </div>
                    <table data-report="detailContent" class="table">
                        <thead>
                            <tr>
                                <th>Permisos Descuentos</th>
                                <th>Ext Diu Fes Dom</th>
                                <th>Ext Diu Ord</th>
                                <th>Ext Noc Fes Dom</th>
                                <th>Ext Noc Ord</th>
                                <th>Rec Fes Diu</th>
                                <th>Rec Fes Noc</th>
                                <th>Rec Noc</th>
                                <th>Rec Ord Fes Noc</th>
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
                        <tfoot>
                            <tr>
                                <th colspan="9">Nota: Se presenta las horas registradas en la plataforma, favor tener presente que estas deben ser aprobadas por el jefe de área</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i></button>
                <button type="button" class="btn btn-primary" data-action="print"><i class="fa fa-print"></i></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>