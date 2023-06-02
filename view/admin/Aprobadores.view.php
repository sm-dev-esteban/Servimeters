<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-body">
                <form id="add">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="nombre">Nombre</label>
                            <input type="text" name="data[nombre]" id="nombre" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="correo">E-mail</label>
                            <input type="text" name="data[correo]" id="correo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="tipo">Tipo</label>
                            <select name="data[tipo]" id="tipo" class="form-control select2" style="width: 100%">
                                <option value="NA">NA</option>
                                <option value="Jefe">Jefe</option>
                                <option value="Gerente">Gerente</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="gestiona">Gestiona</label>
                            <select name="data[gestiona]" id="gestiona" class="form-control select2" style="width: 100%">
                                <option value="NA">NA</option>
                                <option value="RH">RH</option>
                                <option value="Gerente">Gerente</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="esAdmin">Es Administrador</label>
                            <select name="data[esAdmin]" id="esAdmin" class="form-control select2" style="width: 100%">
                                <option value="No">No</option>
                                <option value="Si">Si</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table" id="listApro">
                        <thead class="shadow">
                            <tr>
                                <th> Nombre </th>
                                <th> E-mail </th>
                                <th> Tipo </th>
                                <th> Gestiona </th>
                                <th> Es Administrador </th>
                                <th> Editar </th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th> Nombre </th>
                                <th> E-mail </th>
                                <th> Tipo </th>
                                <th> Gestiona </th>
                                <th> Es Administrador </th>
                                <th> Editar </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>