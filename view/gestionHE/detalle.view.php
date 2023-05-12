<div id="detalleView">
    <span id="closeModal" style="margin-left: 97%; font-weight: bold;">X</span>
    <div style="padding: 0 50px 0 50px;">
        <h3>Detalle de Hora Extra <span style="font-weight: bold;"><?php if (isset($_POST['object'])){ echo $_POST['object']['id_reporteHE'];} ?></span></h3>
        <table style="border: 1px solid black; border-collapse: collapse;">
            <thead>
                <tr id="headTableDetail" style="border-bottom: 1px solid black; font-size: 16px; background-color: azure;">
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Novedad</th>
                    <th>Descuento</th>
                </tr>
            </thead>
            <tbody id="bodyTableDetail" style="border: 1px solid black;">
            </tbody>
        </table>
    </div>
</div>