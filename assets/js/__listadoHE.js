$(document).ready(async function (e) {
    config = await loadConfig();

    const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

    var user = $('#usuarioLogin').html();

    var object = {
        'object': {
            'empleado': user
        }
    }

    $.when($.ajax({ data: object, url: '../controller/CRUD.controller.php?action=execute&model=HoraExtra&crud=getListado', type: 'post' }))
        .then(function (result1) {

            console.log('Cargando datos..');

            //Cargar Datos de Tabla
            localStorage.setItem('arrayListado', result1);
            var datos = JSON.parse(result1);
            var html;

            datos.forEach((dato, index) => {
                html += '<tr>';
                var anno = dato.fechaFin;
                anno = new Date(anno);

                html += `<td>${dato.id}</td>`;
                html += `<td>${dato.cc}</td>`;
                html += `<td>${dato.cecoName ? dato.cecoName : 'N/A'}</td>`;
                html += `<td>${dato.claseName ? dato.claseName : 'N/A'}</td>`;
                html += `<td>${anno.getFullYear()}</td>`;
                html += `<td>${meses[anno.getMonth()]}</td>`;
                html += `<td>${dato.aprobadorNombre ? dato.aprobadorNombre : 'N/A'}</td>`;
                html += `${dato.estadoNombre.includes('Rechazado') ? '<td style="color: #e44c65; font-weight: bold;">' + dato.estadoNombre + '</td>' : dato.estadoNombre.includes('Edicion') ? '<td style="color: #5475c7; font-weight: bold;">' + dato.estadoNombre + '</td>' : '<td>' + dato.estadoNombre + '</td>'}`;
                html += `<td>${dato.estadoNombre.includes('Rechazado') || dato.estadoNombre.includes('Edicion') ? '<a href="#" data-index="' + index + '" class="button primary small editarRegistro">Editar</a>' : '<span data-reporte="' + dato.id + '" class="openDetails icon solid fa-eye fit" id="detalle_' + dato.id + '"></span>'} </td>`;

                html += '</tr>';

            });

            $('#tableBody').html(html);
            $('#dataTable').DataTable({
                paging: false,
                "language": {
                    "search": "Ingrese un valor para filtrar la tabla: ",
                    "info": "Hay _TOTAL_ registros",
                    "zeroRecords": "No hay registros para mostrar"
                },
                dom: '<"top"if>rt<"clear">',
                stateSave: true,
            });

            coloriceTable();
            editar();
        });
});


function coloriceTable() {
    $('#dataTable tbody').on('mouseenter', 'td', function () {
        var colIdx = $('#dataTable').DataTable().cell(this).index().column;

        $($('#dataTable').DataTable().cells().nodes()).removeClass('highlight');
        $($('#dataTable').DataTable().column(colIdx).nodes()).addClass('highlight');
    });
}

function modal() {

    var style = "<link rel='stylesheet' href=\"../assets/css/load.css\"></link>";

    $('#links').append(style);

    var html = `<div class="animate__animated animate__jackInTheBox" id="contentModal">
    <span id="textoLoad">Cargando ...</span>
    </div>
    <div class="load-9">
    <div class="spinner">
    <div class="bubble-1"></div>
    <div class="bubble-2"></div>
    </div>`;
    $('#myModal').html(html);
    $('#myModal').css({ display: 'block' });

}

function hideModal() {
    return new Promise((resolve, reject) => {
        let modal = $('#myModal');
        modal.html('');
        modal.css({ display: 'none' });
        resolve(true);
    })
}

function editar() {

    $('.editarRegistro').on('click', function () {
        var index = $(this).data('index');

        var data = localStorage.getItem('arrayListado');
        var script = "<script src=\"../assets/js/reporteHE.js\"></script>";
        var script_two = "<script src=\"../assets/js/editarHE.js\"></script>";
        var style = "<link rel='stylesheet' href=\"../assets/css/load.css\"></link>";

        data = JSON.parse(data);

        data = data[index];

        var object = {
            'object': {
                'id_reporteHE': data.id
            }
        }

        $.when($.ajax({ data: data, url: './estado/editarHE.view.php', type: 'post' }), $.ajax('./reportar/index.view.php'), $.ajax({ data: object, url: '../controller/CRUD.controller.php?action=execute&model=HoraExtra&crud=getCantHorasExtraByReport', type: 'post' }), $.ajax({ data: object, url: '../controller/CRUD.controller.php?action=execute&model=Recargo&crud=getCantRecargosByReport', type: 'post' }), $.ajax({ data: object, url: '../controller/CRUD.controller.php?action=execute&model=HoraExtra&crud=getHorasExtraByReport', type: 'post' }))
            .then(function (result1, result2, result3, result4, result5) {
                if (data.id_estado !== config.EDICION.toString()) {
                    $('#links').append(script_two, style);
                }

                modal();

                var arrayDetailsHE = JSON.parse(result3[0]);
                var arrayRecargo = JSON.parse(result4[0]);
                var arrayHE = JSON.parse(result5[0]);

                //Cargar HTML Editar
                $('#result').html(result1[0]);

                // Cargar HTML Reporte
                $('#formReporte').html(result2[0]);

                //Cargar Datos de Reporte, HE, Recargos, Comentarios
                $('#heReportadas').css('display', 'inline');
                setEncabezados(arrayDetailsHE);
                setEncabezados(arrayRecargo);

                var fecha = data.fechaFin;
                fecha = new Date(fecha);

                var month = fecha.getMonth();
                month = month + 1;

                if (month < 10) {
                    month = '0' + month;
                }

                setTimeout(() => {

                    seeComments(object);

                    //Cargar datos de HE, Recargos
                    printDetalleHE(arrayHE, arrayDetailsHE, arrayRecargo);

                    $('#links').append(script);

                    $('#sendData').attr('data-type', 'update');

                    $('#cc').val(data.cc);

                    $('#cargo').val(data.cargo);

                    $('#total').html(data.total);

                    $('#mes').val(fecha.getFullYear() + '-' + month);

                    $('#correoEmpleado').val(data.correoEmpleado);

                    fieldsEdit();
                    buttonAllowRows();

                }, 3000);

                setTimeout(async () => {

                    if (data.id_estado == config.RECHAZO.toString()) {
                        if (data.aprobadorTipo == 'Jefe') {
                            $("#jefe").prop("checked", true);
                            $('#listJefe').attr('disabled', false);
                            $(`#listJefe option[value=${data.id_aprobador}]`).attr("selected", true);

                            var correoAprobador = $('#listJefe').find(':selected').data('correoaprobador');
                            var aprobador = $('#listJefe').find(':selected').val();
                            localStorage.setItem('correoAprobador', correoAprobador);
                            localStorage.setItem('aprobador', aprobador);
                            localStorage.setItem('TipoAprobador', 'Jefe');


                        } else if (data.aprobadorTipo == 'Gerente') {
                            $("#gerente").prop("checked", true);
                            $('#listGerente').attr('disabled', false);
                            $(`#listGerente option[value=${data.id_aprobador}]`).attr("selected", true);

                            var correoAprobador = $('#listGerente').find(':selected').data('correoaprobador');
                            var aprobador = $('#listGerente').find(':selected').val();
                            localStorage.setItem('correoAprobador', correoAprobador);
                            localStorage.setItem('aprobador', aprobador);
                            localStorage.setItem('TipoAprobador', 'Gerente');
                        }
                    } else if (data.id_estado == config.RECHAZO_RH.toString() || data.id_estado == config.RECHAZO_CONTABLE.toString()) {
                        $("#jefe").attr("disabled", true);
                        $('#listJefe').attr('disabled', true);
                        $("#gerente").attr("disabled", true);
                        $('#listGerente').attr('disabled', true);

                        var correoAprobador = data.correoAprobador;
                        var aprobador = data.aprobador;
                        localStorage.setItem('correoAprobador', correoAprobador);
                        localStorage.setItem('aprobador', aprobador);
                        if (data.estado == '1006') {
                            localStorage.setItem('TipoAprobador', 'contable');
                        } else if (data.estado == '1007') {
                            localStorage.setItem('TipoAprobador', 'rh');
                        }
                    } else if (data.id_estado == config.EDICION.toString()) {
                        localStorage.setItem('correoAprobador', '');
                        localStorage.setItem('aprobador', '');
                        localStorage.setItem('TipoAprobador', '');
                    }

                    if (data.id_ceco) {
                        $(`#ceco option[value=${data.id_ceco}]`).attr("selected", true);
                    }

                    printSummaries(arrayDetailsHE);
                    printSummaries(arrayRecargo);

                    try {
                        await hideModal();
                    } catch (e) {
                        console.log(e);
                    }


                }, 5000);

            });
    })

}

function setEncabezados(data) {
    let titulo = [];
    let html;
    data.forEach(element => {
        if (!titulo.includes(element.nombre)) {
            titulo.push(element.nombre);
        }
    });

    titulo.forEach(element => {
        html += `<th>${element}</th>`;
    });

    $('#headTableEdit').append(html);
}

function printDetalleHE(data1, data2, data3) {
    let html;
    let cantHE = data2.length / data1.length;
    let cantRecargo = data3.length / data1.length;
    let indiceHE = 0;
    let indiceRec = 0;
    let sumaHE = 0;
    let sumaRecargo = 0;
    let sumaDescuento = 0;

    for (let i = 0; i < data1.length; i++) {
        html += `<tr id="row_${data1[i].id}" style="border-bottom: 1px solid black; font-size: 13px;">
                <td><input type="date" class="fieldEdit fechasActividades" name="" id="" value="${data1[i].fecha}" required/></td>
                <td><input type="text" class="fieldEdit novedad" name="" id="" value="${data1[i].novedad}" style="font-size: 12px;" required></td>
                <td><input type="text" class="fieldEdit values descuentos" name="" value="${data1[i].descuento}" /></td>`;
        sumaDescuento += parseFloat(data1[i].descuento);

        for (let j = indiceHE; j < (cantHE + indiceHE); j++) {
            html += `<td><input type="text" class="fieldEdit values valueHE" name="" data-id="${data2[j].nombre.replaceAll(' ', '')}" data-codigo="${data2[j].tipo_horaExtra}" value="${data2[j].cantidad}" required pattern="^[0-9]{1,2}?(.[0,5]{0,1})?$" title="Solo numeros, debe terminar en un decimal .5 o en la unidad mas próxima"/></td>`;
            sumaHE += parseFloat(data2[j].cantidad);
        }

        for (let k = indiceRec; k < (cantRecargo + indiceRec); k++) {
            html += `<td><input type="text" class="fieldEdit values valueRecargo" name="" data-id="${data3[k].nombre.replaceAll(' ', '')}" data-codigo="${data3[k].tipo_recargo}" value="${data3[k].cantidad}" required pattern="^[0-9]{1,2}?(.[0,5]{0,1})?$" title="Solo numeros, debe terminar en un decimal .5 o en la unidad mas próxima"/></td>`;
            sumaRecargo += parseFloat(data3[k].cantidad);
        }

        html += `<td><span style="color: tomato;" data-id="${data1[i].id}" class="deleteRow icon solid fa-window-close fi" onclick="deleteRow(event, this, true)"></span></td>`;
        html += `<td><span style="color: greenyellow; font-size: 15px;" data-id="${data1[i].id}" class="updateRow icon solid fa-check-circle fi" onclick="updateRow(event, this)"></span></td>`;

        indiceRec += cantRecargo;
        indiceHE += cantHE;
        html += `</tr>`;
    }

    $('#calcHE').html(sumaHE);
    $('#calcRec').html(sumaRecargo);
    $('#calcDescuentos').html(sumaDescuento);
    $('#bodyTableEdit').append(html);
}

function fieldsEdit() {
    $('.fieldEdit').change(function () {
        $(this).addClass('editado');
    });
}

function buttonAllowRows() {
    $('#allowAddRows').click(function () {
        $('#tableHE').removeClass("sectionDisabled");
        $(this).removeClass('fa-toggle-off');
        $(this).addClass('fa-toggle-on');
        $(this).html('Cancelar');
        $(this).css('background-color', 'tomato');
        $(this).removeAttr('id');
        $(this).attr('id', 'deniedAddRows');

        buttonDeniedRows();
    });
}

function buttonDeniedRows() {
    $('#deniedAddRows').click(function () {
        $('#tableHE').addClass("sectionDisabled");
        $(this).removeClass('fa-toggle-on');
        $(this).addClass('fa-toggle-off');
        $(this).html('Agregar Horas Extra');
        $(this).css('background-color', '#5480f1');
        $(this).removeAttr('id');
        $(this).attr('id', 'allowAddRows');

        buttonAllowRows();
    });
}

function printSummaries(arrayValues) {
    for (let i = 0; i < arrayValues.length; i++) {
        var idSummary;
        var summaryValue;

        idSummary = arrayValues[i].nombre.replaceAll(' ', '');
        summaryValue = parseFloat($(`#summary_${idSummary}`).html());
        summaryValue += parseFloat(arrayValues[i].cantidad);
        $(`#summary_${idSummary}`).html(summaryValue);
    }
}

function seeComments(object) {
    $('#seeComments').on('click', function () {
        $.ajax({
            data: object,
            url: '../controller/CRUD.controller.php?action=execute&model=Comentario&crud=getComments',
            type: 'post',
            success: function (result) {
                var arrayComments = JSON.parse(result);

                var htmlComments;

                arrayComments.forEach(element => {
                    htmlComments += '<tr>';
                    htmlComments += `<td style="text-align: left" id="comment-${element.id}">${element.creadoPor} - ${element.fecha}</td>`;
                    htmlComments += '</tr>';
                    htmlComments += '<tr>';
                    htmlComments += `<td>${element.cuerpo}</td>`;
                    htmlComments += '</tr>';
                });

                //Cargar comentarios
                $('#bodyComments').html(htmlComments);

                $(`#seeComments`).css({ display: 'none' });
                $(`#hideComments`).css({ display: 'inline' });
                hideComments();
            }
        })

    });
}

function hideComments() {
    $('#hideComments').click(function () {

        $('#bodyComments').html('');

        $(`#seeComments`).css({ display: 'inline' });
        $(`#hideComments`).css({ display: 'none' });
    });
}