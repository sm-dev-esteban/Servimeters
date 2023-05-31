<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-body">
                <form id="add">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="titulo">Nombre</label>
                            <input type="text" name="data[titulo]" id="titulo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="id_clase">Clase</label>
                            <select name="data[id_clase]" id="id_clase" class="form-control select2" style="width: 100%"></select>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table" id="listCC">
                        <thead class="shadow">
                            <tr>
                                <th>Título</th>
                                <th>Clase</th>
                                <th>Editar</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Título</th>
                                <th>Clase</th>
                                <th>Editar</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>