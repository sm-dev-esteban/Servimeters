$(document).ready(async function(e) {
    config = await loadConfig();
    
    selectAprobador();
    sumDescuento();
    setDataAprobador();
    sendData();
    showHelp();

    $.notify.defaults({ className: "info" });

    $.notify.addStyle('happyblue', {
        html: "<b>⚠ <span data-notify-text/>⚠</b>",
        classes: {
          base: {
            "white-space": "nowrap",
            "background-color": "#D9EDF7",
            "color": "#3a87ad",
            "padding": "5px",
            "font-size": "15px !important"
          }
        }
      });

    $('#formReporte').validate({
        rules: {
            cc: 'required',
            cargo: 'required',
            mes: 'required',
            novedad: 'required',
            correoEmpleado: 'required'
        },
        messages: {
            cc: 'La CC no debe tener puntuación, debe contener al menos 10 digitos.',
            cargo: 'Ingrese un valor para cargo.',
            mes: 'Seleccione el mes a reportar.',
            novedad: 'Ingrese la causa de la novedad.',
            correoEmpleado: 'Ingrese un correo valido',
        }
    });

    $.when($.ajax('../controller/CRUD.controller.php?action=listAll&model=CentroCosto&crud=get'), $.ajax('../controller/CRUD.controller.php?action=listAll&model=TipoHE&crud=get'), $.ajax('../controller/CRUD.controller.php?action=listAll&model=TipoRecargo&crud=get'), $.ajax('../controller/CRUD.controller.php?action=listAll&model=Aprobador&crud=get'))
    .then(function (result1, result2, result3, result4) {
        // Cargar CECOS
        cargarLista(result1[0], 'ceco');

        //Celdas de Resumen
        var summaryCells = '';

        // Cargar Tabla HE
        var headerTable = '';
        var bodyTable = '';
        var id;
        var datos = JSON.parse(result2[0]);

        datos.forEach(element => {
            id = element.nombre.replaceAll(' ', '');

            headerTable += `<th>${element.nombre}</th>`;

            bodyTable += `<td style="width: 70px;"><input type="text" class="values valueHE" name="${id}" data-id="${id}" data-codigo="${element.codigo}" value="0" required pattern="^[0-9]{1,2}?(.[0,5]{0,1})?$" title="Solo numeros, para decimales debe terminar en .5"/></td>`
            
            summaryCells += `<td id="summary_${id}" class="summariesFields">0</td>`;
        });

        //headerTable += '</tr>';
        //bodyTable += '</tr>';

        $('#encTableHE').append(headerTable);
        $('#rowTableHE').append(bodyTable);

        // Cargar Table Recargos
        headerTable = '';
        bodyTable = '';
        id;
        datos = JSON.parse(result3[0]);

        datos.forEach(element => {
            id = element.nombre.replaceAll(' ', '');

            headerTable += `<th>${element.nombre}</th>`;

            bodyTable += `<td style="width: 70px;"><input type="text" class="values valueRecargo" name="${id}" data-id="${id}" data-codigo="${element.codigo}" value="0" required pattern="^[0-9]{1,2}?(.[0,5]{0,1})?$" title="Solo numeros, para decimales debe terminar en .5"/></td>`
            summaryCells += `<td id="summary_${id}" class="summariesFields">0</td>`;
        });

        //headerTable += '</tr>';
        //bodyTable += '</tr>';

        headerTable += `<th>Acción</th>`;

        $('#encTableHE').append(headerTable);
        $('#rowTableHE').append(bodyTable);
        $('#summaries').append(summaryCells);

        //Cargar listado Jefes/Gerentes
        var htmlJefe;
        var htmlGerente;
        var datos = JSON.parse(result4[0]);

        htmlJefe += `<option value="0">-- Seleccione un Jefe --</option>`;
        htmlGerente += `<option value="0">-- Seleccione un Gerente --</option>`;

        datos.forEach(element => {
            if (element.tipo == 'Gerente') {
                htmlGerente += `<option value="${element.id}" data-correoAprobador="${element.correo}">${element.nombre}</option>`;
            }else if (element.tipo == 'Jefe') {
                htmlJefe += `<option value="${element.id}" data-correoAprobador="${element.correo}">${element.nombre}</option>`;
            }
        });

        $('#listJefe').html(htmlJefe);
        $('#listGerente').html(htmlGerente);

        let lenghtCol =  countColumns();
        colspanButton(lenghtCol);
        changeDateReport();
        addRow();
        focusValuesHE();
        sumValuesHE();
        sumValuesRecargo();

        summaryValues();

        let dataType = $('#sendData').data('type');
        if (dataType !== 'update'){
            validateMainValues();
        }

        onLoadEditHE();
    });
    
});

function showHelp() {
    $('.help').on('mouseover', function() {
        $(this).notify('Digite los decimales separados por punto "."".\n Redondee los decimales de modo que sea .5 o a la unidad mas proxima.', {style: 'happyblue', position: 'right', autoHideDelay: 3500} )
    })
}

function selectAprobador() {
    $('#jefe').click(function(e) {
        $('#listJefe').attr('disabled', false);
        $('#listGerente').find('option:first-child').prop('selected', true);
        localStorage.setItem('correoAprobador', '');
        localStorage.setItem('aprobador', '');
        localStorage.setItem('TipoAprobador', '');
        $('#listGerente').attr('disabled', true);
        $('#errorRadio').css({display: 'none'});
    });

    $('#gerente').click(function(e) {
        $('#listJefe').attr('disabled', true);
        $('#listJefe').find('option:first-child').prop('selected', true);
        localStorage.setItem('correoAprobador', '');
        localStorage.setItem('aprobador', '');
        localStorage.setItem('TipoAprobador', '');
        $('#listGerente').attr('disabled', false);
        $('#errorRadio').css({display: 'none'});
    })
}

function setDataAprobador() {
    $('#listJefe').on('change', function(e) {
        var correoAprobador = $(this).find(':selected').data('correoaprobador');
        var aprobador = $(this).find(':selected').val();
        localStorage.setItem('correoAprobador', correoAprobador);
        localStorage.setItem('aprobador', aprobador);
        if (aprobador !== '0'){
            localStorage.setItem('TipoAprobador', 'Jefe');
        }else{
            localStorage.setItem('TipoAprobador', '');
        }
        $('#errorRadio').css({display: 'none'});
    });

    $('#listGerente').on('change', function(e) {
        var correoAprobador = $(this).find(':selected').data('correoaprobador');
        var aprobador = $(this).find(':selected').val();
        localStorage.setItem('correoAprobador', correoAprobador);
        localStorage.setItem('aprobador', aprobador);
        if (aprobador !== '0'){
            localStorage.setItem('TipoAprobador', 'Gerente');
        }else{
            localStorage.setItem('TipoAprobador', '');
        }
        $('#errorRadio').css({display: 'none'});
    })
}

function validateInput(inputValue) {
    const regex = /^(0|[1-9]\d*)(\.5|\.0)?$/;
    return regex.test(parseFloat(inputValue));
}

function focusValuesHE() {
    
    $('.values').on('focus', function(e) {

        var valorActual = $(this).val();
        if (valorActual == '0' || isNaN(valorActual) || !validateInput(valorActual)) {
            $(this).val('');
        }
    });

    $('.values').on('blur', function(e) {

        var valorActual = $(this).val();
        if (valorActual == '' || isNaN(valorActual) || !validateInput(valorActual)) {
            $(this).val('0');
            return;
        }

        if (parseFloat(valorActual) < 0) {
            $(this).val( Math.abs(valorActual) );
            return;
        }
    })
}

function sumValuesHE() {

    var suma;

    $('.valueHE').on('focus', function(e) {

        suma = $('#calcHE').html();
        suma = parseFloat(suma);
        
        var valorHE;

        valorHE = $(this).val();
        
        if (valorHE == '' || isNaN(valorHE) || !validateInput(valorHE)) {
            valorHE = 0;
        }

        if (valorHE !== '0.0' || valorHE !== 0 || valorHE !== '0') {
            suma -= parseFloat(valorHE);
        }
        
    });

    $('.valueHE').on('blur', function(e) {    
        var valorHE;

        valorHE = $(this).val();
        
        if (valorHE == '' || isNaN(valorHE) || !validateInput(valorHE)) {
            valorHE = 0;
        }

        if (!isNaN(valorHE)) {
            suma += parseFloat(valorHE);
            colorSum(suma, 'calcHE');
            if (suma > config.LIMIT_HE) {
                suma -= parseFloat(valorHE);
                $(this).val(0);
                setTimeout(()=>{
                    alert('Exede el numero de horas extra permitidas');
                }, 150);
            }
            
        }

        $('#calcHE').html(suma);
        total();
    });

}

function sumValuesRecargo() {
    var suma;

    $('.valueRecargo').on('focus', function(e) {

        suma = $('#calcRec').html();
        suma = parseFloat(suma);
        
        var valorHE;

        valorHE = $(this).val();

        if (valorHE == '' || isNaN(valorHE) || !validateInput(valorHE)) {
            valorHE = 0;
        }

        if (valorHE !== '0.0' || valorHE !== 0 || valorHE !== '0') {
            suma -= parseFloat(valorHE);
        }
        
    });

    $('.valueRecargo').on('blur', function(e) {    
        var valorHE;

        valorHE = $(this).val();

        if (valorHE == '' || isNaN(valorHE) || !validateInput(valorHE)) {
            valorHE = 0;
        }

        if (!isNaN(valorHE)) {
            suma += parseFloat(valorHE);
        }

        $('#calcRec').html(suma);
        total();

    });

}

function sumDescuento() {

    var suma;

    $('.descuentos').on('focus', function(e) {

        suma = $('#calcDescuentos').html();
        suma = parseFloat(suma);

        var valorHE;

        valorHE = $(this).val();

        if (valorHE == '' || isNaN(valorHE) || !validateInput(valorHE)) {
            valorHE = 0;
        }

        if (valorHE !== '0.0' || valorHE !== 0 || valorHE !== '0') {
            suma -= parseFloat(valorHE);
        }

    });

    $('.descuentos').on('blur', function(e) {
        var valorHE;

        valorHE = $(this).val();

        if (valorHE == '' || isNaN(valorHE) || !validateInput(valorHE)) {
            valorHE = 0;
        }


        if (!isNaN(valorHE)) {
            suma += parseFloat(valorHE);
        }

        $('#calcDescuentos').html(suma);

        total();
    });
}

function colorSum(suma, id) {
    switch (true) {
        case suma >= 0 && suma < 20:
            $(`#${id}`).css({color: 'greenyellow'});
            break;
        case suma >= 20 && suma < 35:
            $(`#${id}`).css({color: 'orange'});
            break;
        case suma >= 35 && suma < 48:
            $(`#${id}`).css({color: '#ff6600'});
            break;
        case suma >= 48 || suma < 0:
            $(`#${id}`).css({color: 'red'});
            break;
        case suma < 0:
            $(`#${id}`).css({color: 'red'});
            break;
        default:
            break;
    }
}

function total() {
    var total;

    var horasExtra = $('#calcHE').html();
    var recargos = $('#calcRec').html();
    var descuentos = $('#calcDescuentos').html();

    total = parseFloat(Math.abs(horasExtra)) + parseFloat(Math.abs(recargos)) + parseFloat(Math.abs(descuentos));

    $('#total').html(total);

}

function sendData() {
    $('#sendData').click(function(e) {
        e.preventDefault();
        var idHorasExtra;
        let idReporteHE;

        //DATOS DE REPORTE HE
        var cc = $('#cc').val();
        var cargo = $('#cargo').val();
        var correoEmpleado = $('#correoEmpleado').val();
        var ceco = $('#ceco').children("option:selected").val();
        var total = $('#total').html();
        var empleado = $('#cc').data('empleado');
        var proyecto = $('#proyecto').val();
        //***************

        if (cc.length <= 0 || empleado.length <= 0 || cargo.length <= 0) {
            $(this).notify("Hay campos requeridos (*) que estan vacios!", 'error');
            console.log('Hay datos vacios');
            return false;
        }

        if (cc.length > 10) {
            $(this).notify("El numero de Cedula no es valido!", 'error');
            console.log('Error en la cedula');
            return false;
        }

        if (ceco.length <= 0){
            ceco = null;
        }

        if (proyecto.length <= 0){
            proyecto = null;
        }

        var fechas = getFechas();
        
        if ( !Array.isArray(fechas) ) {
            $(this).notify("Seleccione una fecha de reporte!", 'error');
            console.log('Error al generar fechas');
            return false;
        }

        var aprobador;
        var correoAprobador;

        if( $('input[name="aprobador"]').is(':checked')) {
            
            aprobador = localStorage.getItem('aprobador');
            correoAprobador = localStorage.getItem('correoAprobador');

        }else{

            let type =  localStorage.getItem('TipoAprobador');
            if (type == 'contable' || type == 'rh'){
                aprobador = localStorage.getItem('aprobador');
                correoAprobador = localStorage.getItem('correoAprobador');
            }
        }

        if (!aprobador){
            aprobador = null;
        }

        if (!correoAprobador){
            correoAprobador = null;
        }

        var estado = getEstado();

        if (estado.length <= 0) {
            $(this).notify("No hay un estado para agregar!", 'error');
            return false;
        }

        let validate = validateDataRows();

        if (!validate){
            return false;
        }

        let object = {
            'object': {
                'id_estado': estado,
                'id_ceco': ceco,
                'total': total,
                'id_aprobador': aprobador,
                'empleado': empleado,
                'correoEmpleado': correoEmpleado,
                'cc': cc,
                'cargo': cargo,
                'proyecto': proyecto,
                'fechaInicio': fechas[2],
                'fechaFin': fechas[1]
            }
        }

        /**

        $.ajax({
            data: object,
            url: './test.php',
            type: 'post',
            success: function(result){
                $('#resultTest').html(result);
                return false;
            }

        });

         **/

        var actionExecute = $(this).data('type');

        switch (actionExecute) {
            case 'create':
                $.ajax({
                    data: object,
                    url: '../controller/CRUD.controller.php?action=execute&model=Reporte&crud=insert',
                    type: 'post',
                    beforeSend: function () {

                        $('#butonSend').css({display: 'none'});
                        $('#loadSpinner').css({display: 'inline'});

                        localStorage.setItem('correoAprobador', '');
                        localStorage.setItem('aprobador', '');
                        localStorage.setItem('TipoAprobador', '');

                        console.log('Correo Aprobador ', correoAprobador);
                    },
                    success: async function(result){

                        console.log('Result ', result);

                        if (isNaN(parseInt(result))) {
                            $.notify('Error al registrar las Horas Extra.', 'error');
                            return false;
                        }

                        idReporteHE = result;

                        if (!document.getElementById('tableHE').classList.contains('sectionDisabled')){
                            try {
                                await createHE(idReporteHE);
                            }catch (e) {
                                console.log('Error al crear HE', e);
                            }
                        }

                        $('#formReporte').trigger("reset");
                        $('#total').html(0);
                        $('#calcRec').html(0);
                        $('#calcHE').html(0);
                        clearSummaryFields();

                        $('#listGerente').find('option:first-child').prop('selected', true);
                        $('#listJefe').find('option:first-child').prop('selected', true);
                        $('#listJefe').attr('disabled', true);
                        $('#listJefe').attr('disabled', true);
                        clearTable();
                        $("#tableHE").addClass("sectionDisabled");
                        $.notify('Enviado con exito', 'success');

                        if (estado === config.EDICION){
                            $('#butonSend').css({display: 'inline'});
                            $('#loadSpinner').css({display: 'none'});
                            return true;
                        }

                        let data = {
                            'to': correoAprobador,
                            'from': correoEmpleado,
                            'empleado': empleado,
                            'idReporte': idReporteHE
                        }

                        $.ajax({
                            data:  data,
                            url: '../controller/Email.controller.php?email=solicitudEmpleado',
                            type: 'post',
                            success: function(result){

                                console.log('Email result ', result);

                                if (result == 'Message has been sent1') {
                                    $.notify('Notificación enviada', 'success');
                                }else{
                                    $.notify('No se envió la notificación', 'error');
                                }

                                $('#butonSend').css({display: 'inline'});
                                $('#loadSpinner').css({display: 'none'});
                                return true;

                            }

                        });

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $.notify('Error al crear el registro.', 'error');
                        console.error(title, 'textStatus::' + textStatus, 'errorThrown:: ' + errorThrown);
                    }
                });
                break;
            case 'update':

                console.log('Actualizando...');

                let updateRows = document.getElementsByClassName('fieldEdit');

                for (let updateRow of updateRows) {
                    if (updateRow.classList.contains('editado')){
                        $.notify('Tiene registros sin guardar!', 'info');
                        updateRow.focus();
                        return;
                    }
                }

                idReporteHE = $('#idReporteHE').data('id');

                object.object.id = idReporteHE;

                console.log(object);

                $.ajax({
                    data: object,
                    url: '../controller/CRUD.controller.php?action=execute&model=Reporte&crud=update',
                    type: 'post',
                    beforeSend: function () {
                        $('#butonSend').css({display: 'none'});
                        $('#loadSpinner').css({display: 'inline'});

                        localStorage.setItem('correoAprobador', '');
                        localStorage.setItem('aprobador', '');
                        localStorage.setItem('TipoAprobador', '');
                    },
                    success: async function(result){

                        console.log('UPDATE REPORTE ', result);

                        if (result !== '1'){
                            $.notify('Error al actualizar el reporte', 'error');
                        }else{
                            $.notify('Reporte actualizado con exito', 'success');
                        }

                        $('#butonSend').css({display: 'inline'});
                        $('#loadSpinner').css({display: 'none'});

                        if (!document.getElementById('tableHE').classList.contains('sectionDisabled')){
                            let execution = await createHE(idReporteHE);

                            if (!execution){
                                return false;
                            }
                        }

                        $.when($.ajax('./estado/listEstado.view.php'))
                            .then(function(result1) {
                                var script = "<script src=\"../assets/js/listadoHE.js\"></script>";
                                var script2 = "<script src=\"../assets/js/detailsReporte.js\"></script>";
                                //Cargar HTML
                                $('#links').append(script2);
                                $('#links').append(script);

                                $('#result').html(result1);

                                if (estado === config.EDICION){
                                    return true;
                                }

                                var data = {
                                    'to': correoAprobador,
                                    'from': correoEmpleado,
                                    'empleado': empleado,
                                    'idReporte': idReporteHE
                                }

                                $.ajax({
                                    data:  data,
                                    url: '../controller/Email.controller.php?email=actualizacionHE',
                                    type: 'post',
                                    success: function(result){

                                        console.log('Result Email ', result);

                                        if (result == 'Message has been sent1') {
                                            $.notify('Notificación enviada', 'success');
                                            return true;
                                        }

                                        $.notify('No se envió la notificación', 'error');
                                    }

                                });

                            });
                    }
                });

                break;
            default:
                break;
        }

    })
}

function getFechas(){
    var fechas = [];
    var fecha;
    var mes = $('#mes').val();

    if (mes.length <= 0) {
        return;
    }

    mes = mes.split('-');

    if (parseInt(mes[1]) <= 8) {
        mes = mes[0] + '-' + '0' + (parseInt(mes[1]) + 1);
    }else if (parseInt(mes[1]) >= 9 && parseInt(mes[1]) <= 11) {
        mes = mes[0] + '-' + (parseInt(mes[1]) + 1);
    }else if (parseInt(mes[1]) == 12) {
        mes = (parseInt(mes[0]) + 1) + '-' + '01';
    }

    fecha = new Date();
    fechas[0] = fecha.getFullYear() + '-' + (fecha.getMonth() + 1) + '-' + fecha.getDate();
    fecha = new Date(mes);

    var month;
    var year;
    if (fecha.getMonth() <= 9){
        if (fecha.getMonth() == 0) {
            month = '12';
            year = (fecha.getFullYear() - 1);
        }else{
            month = '0' + fecha.getMonth();
            year = fecha.getFullYear();
        }
    }else{
        month = fecha.getMonth();
        year = fecha.getFullYear();
    }

    fechas[2] = year + '-' + month + '-01';

    if (fecha.getMonth() <= 8){
        fechas[1] = fecha.getFullYear() + '-0' + (fecha.getMonth() + 1)  + '-' + fecha.getDate();
    }else{
        fechas[1] = fecha.getFullYear() + '-' + (fecha.getMonth() + 1)  + '-' + fecha.getDate();
    }

    return fechas;
}

function validateDataRows(){

    if (document.getElementById('tableHE').classList.contains('sectionDisabled')){
        return true;
    }
    let regex = new RegExp('^[0-9]{1,2}?(.[0,5]{0,1})?$');

    let rowsTable = document.getElementById("bodyTableHE");
    let rowElements = rowsTable.getElementsByTagName("tr");
    let isError = false;

    for (let rowElement of rowElements) {
        let inputElements = rowElement.getElementsByTagName("input");

        for (let i = 0; i < inputElements.length; i++) {

            if (inputElements[i].classList.contains('fechasActividades')){
                if (inputElements[i].value.length <= 0){
                    inputElements[i].focus();
                    isError = true;
                    break;
                }
            }

            if (inputElements[i].classList.contains('novedades')){
                if (inputElements[i].value.length <= 0){
                    inputElements[i].focus();
                    isError = true;
                    break;
                }
            }

            if (inputElements[i].classList.contains('descuentos') || inputElements[i].classList.contains('valueHE') || inputElements[i].classList.contains('valueRecargo')){
                if (!regex.test(inputElements[i].value)) {
                    inputElements[i].focus();
                    isError = true;
                    break;
                }
            }
        }

        if (isError){
            $.notify('Hay campos obligatorios (*) vacios o el formato de número es erroneo!', 'error');
            return false;
        }
    }

    return true;
}

function getHE() {

    let arrayHE = [];
    let valuesHE = [];
    let valuesRecargo = [];

    let rowsTable = document.getElementById("bodyTableHE");

    let rowElements = rowsTable.getElementsByTagName("tr");

    for (let rowElement of rowElements) {
        let inputElements = rowElement.getElementsByTagName("input");
        let setValuesHE = [];
        let setValuesRecargo = [];
        let he = {};
        for (let i = 0; i < inputElements.length; i++) {
            let valorHE = {};
            let valorRecargo = {};

            if (inputElements[i].classList.contains('fechasActividades')){
                he.fecha = inputElements[i].value;
            }

            if (inputElements[i].classList.contains('novedades')){
                he.novedad = inputElements[i].value;
            }

            if (inputElements[i].classList.contains('descuentos')){
                he.descuento = inputElements[i].value;
            }

            if (inputElements[i].classList.contains('valueHE')){
                valorHE.codigo = inputElements[i].dataset.codigo;
                inputElements[i].value == '0.0' ? valorHE.value = '0' : valorHE.value = inputElements[i].value;
            }

            if (inputElements[i].classList.contains('valueRecargo')){
                valorRecargo.codigo = inputElements[i].dataset.codigo;
                inputElements[i].value == '0.0' ? valorRecargo.value = '0' : valorRecargo.value = inputElements[i].value;
            }

            if (JSON.stringify(valorHE) !== '{}'){
                setValuesHE.push(valorHE);
            }

            if (JSON.stringify(valorRecargo) !== '{}'){
                setValuesRecargo.push(valorRecargo);
            }
        }

        if (JSON.stringify(he) !== '{}'){
            arrayHE.push(he);
        }

        if (setValuesHE.length > 0){
            valuesHE.push(setValuesHE);
        }

        if (setValuesHE.length > 0){
            valuesRecargo.push(setValuesRecargo);
        }
    }

    return [arrayHE, valuesHE, valuesRecargo];
}

function getDataArray(arrayData, arrayID) {

    for (let i = 0; i < arrayData.length; i++) {
        let values = arrayData[i];
        for (let j = 0; j < values.length; j++) {
            values[j].id = arrayID[i].id;
        }
    }

    return arrayData;
    
}

function getEstado() {
    var tipoAprobador = localStorage.getItem('TipoAprobador');
    var estado;
    
    if(tipoAprobador == 'Jefe'){
        estado = config.APROBACION_JEFE;
    }else if (tipoAprobador == 'Gerente') {
        estado = config.APROBACION_GERENTE;
    }else if (tipoAprobador == 'contable'){
        estado = config.APROBACION_CONTABLE;
    }else if (tipoAprobador == 'rh'){
        estado = config.APROBACION_RH;
    }else{
        estado = config.EDICION;
    }

    return estado;
}

function countColumns(){
    // Obtenemos la fila
    let obtenerFila = document.getElementById("bodyTableHE");

// Obtenemos los elementos td de la fila
    let elementosFila = obtenerFila.getElementsByTagName("td");

// Mostramos la colección HTML de la fila.
    return elementosFila.length;
}

function colspanButton(lenghtCol){
    let celda = document.getElementById('botonAgregar');
    celda.setAttribute('colspan', lenghtCol);

    let celdas = document.getElementsByClassName('tituloTotal');
    for (let i = 0; i < celdas.length; i++) {
        celdas[i].setAttribute('colspan', lenghtCol - 1);
    }
}

function limitDate() {
        let fechas = getFechas();

        let activitiesDate = document.getElementsByClassName('fechasActividades');

        if (fechas == undefined){
            return;
        }

        for (let i = 0; i < activitiesDate.length; i++) {
            activitiesDate[i].setAttribute('max', fechas[1]);
            activitiesDate[i].setAttribute('min', fechas[2]);
        }
}

function addRow() {
    let id = 0;

    $('#agregarhe').click(function (){
        let row = $('#rowTableHE').html();
        id++;

        $('#bodyTableHE').append(`<tr id="row_${id}" class="rowTable">  ${row}  <td align="left" style="width: 30px;"><span title="Eliminar Fila" style="color: tomato;" data-id="${id}" class="deleteRow icon solid fa-window-close fi" onclick="deleteRow(event, this, false)"></span></td> </tr>`);

        const scroll=document.querySelector(".table-wrapper-he");
        scroll.scrollTop=scroll.scrollHeight;

        focusValuesHE();
        sumValuesHE();
        sumValuesRecargo();
        sumDescuento();
        summaryValues();
    });
}

async function deleteRow(e, element, isDelete){

    let id = element.dataset.id;

    let object = {
        'object': {
            'id': id
        }
    }

    if (isDelete){
        try{
            await executeAction(object, 1);
        }catch (e) {
            console.log(e);
            return;
        }
    }

        var restarTotal = 0.0;
        var restarHE = 0.0;
        var restarRecargos = 0.0;
        var restarDescuentos = 0.0;

        let html =  document.getElementById(`row_${id}`);
        let inputs = html.getElementsByTagName("input");

        $(`#row_${id}`).remove();

        for (let i = 0; i < inputs.length; i++) {

            if (inputs[i].classList.contains('descuentos')){
                restarDescuentos += parseFloat(inputs[i].value);
            }

            if (inputs[i].classList.contains('valueHE')){
                restarHE += parseFloat(inputs[i].value);

                var idField = inputs[i].dataset.id;
                var summary = $(`#summary_${idField}`).html();
                summary -= parseFloat(inputs[i].value);
                $(`#summary_${idField}`).html(summary);
            }

            if (inputs[i].classList.contains('valueRecargo')){
                restarRecargos += parseFloat(inputs[i].value);

                var idField = inputs[i].dataset.id;
                var summary = $(`#summary_${idField}`).html();
                summary -= parseFloat(inputs[i].value);
                $(`#summary_${idField}`).html(summary);
            }
        }

        restValues(restarHE, 'calcHE');
        restValues(restarRecargos, 'calcRec');
        restValues(restarDescuentos, 'calcDescuentos');
}

function updateRow(e, element) {
    let id = element.dataset.id;

    let idReport = $('#idReporteHE').data('id');
    let total = $('#total').html();

    let html =  document.getElementById(`row_${id}`);
    let inputs = html.getElementsByTagName("input");

    let HE = {};
    let arrayDetalleHE = [];
    let arrayRec = [];

    for (let i = 0; i < inputs.length; i++) {
        let objectHE = {};
        let objectRec = {};

        if (inputs[i].classList.contains('fechasActividades')){
            HE.fecha = inputs[i].value;
        }

        if (inputs[i].classList.contains('descuentos')){
            HE.descuento = inputs[i].value;
        }

        if (inputs[i].classList.contains('novedad')){
            HE.novedad = inputs[i].value;
        }

        if (inputs[i].classList.contains('valueHE')){
            inputs[i].value == '0.0' ? objectHE.value = '0' : objectHE.value = inputs[i].value;
            objectHE.codigo = inputs[i].dataset.codigo;
        }

        if (inputs[i].classList.contains('valueRecargo')){
            inputs[i].value == '0.0' ? objectRec.value = '0' : objectRec.value = inputs[i].value;
            objectRec.codigo = inputs[i].dataset.codigo;
        }

        if (objectHE.value){
            arrayDetalleHE.push(objectHE);
        }

        if (objectRec.value){
            arrayRec.push(objectRec);
        }

    }

    let object = {
        'object': {
            'id': id,
            'fecha': HE.fecha,
            'novedad': HE.novedad,
            'descuento': HE.descuento,
            'reporte': idReport,
            'total': total
        }
    }

    let data = {
        'horaExtra': id,
        'valuesHE': JSON.stringify(arrayDetalleHE),
        'valuesRecargo': JSON.stringify(arrayRec)
    }

    $.when($.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=Reporte&crud=updateTotal', type: 'post'}), $.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=HoraExtra&crud=update', type: 'post'}), $.ajax({datatype: 'JSON', data: { "data" : data }, url: '../controller/CRUD.controller.php?action=insertMany&model=HoraExtra&crud=updateHoras', type: 'post'}), $.ajax({datatype: 'JSON', data: { "data" : data }, url: '../controller/CRUD.controller.php?action=insertMany&model=Recargo&crud=update', type: 'post'}))
        .then(function (result1, result2, result3, result4){

            if ((result1[0] !== '1') || (result2[0] !== '1') || (result3[0] !== '1') || (result4[0] !== '1')){
                $.notify('Error al actualizar la HE', 'error');
                console.log(result1);
                console.log(result2);
                console.log(result3);
                console.log(result4);
                return;
            }

            for (let input of inputs) {
                input.classList.remove('editado');
            }

            $.notify('Actualizado con exito!', 'success');
        });

}

function restValues(valor, idTotal) {
    let totalVal = 0;
    totalVal = $(`#${idTotal}`).html();
    totalVal = parseFloat(totalVal);

    totalVal -= valor;
    
    $(`#${idTotal}`).html(totalVal);
    total();
}

function validateMainValues(){
    $('.mainValue').change(function (){
        let mainFields = $('.mainValue');
        for (let i = 0; i < mainFields.length; i++) {
            if (mainFields[i].value.length <= 0){
                let table = $('#tableHE');
                if (!table.hasClass('sectionDisabled')){
                    table.addClass('sectionDisabled');
                }
                return;
            }
        }

        $("#tableHE").removeClass("sectionDisabled");
    });
}

function clearTable(){
    let rows = document.getElementsByClassName('rowTable');
    for (let row of rows) {
        row.remove();
    }
}

function executeAction(object, valAction) {
    return new Promise(async (resolve, reject)=>{
        let title;
        let title_2;
        let result = false;

        switch (valAction){
            case 1:
                title = '¿Desea eliminar el registro?';
                title_2 = 'Registro eliminado con exito!';
                break;
            default:
                title = '¿Desea ejecutar la accion?';
                title_2 = 'Accion ejecutada con exito!';
                break;
        }

        swal(title, {
            buttons: ["No!", "Si!"],
        }).then(async (val)=>{
            if (val){
                try {
                    await action(object, valAction);
                    $.notify(title_2);
                    resolve(true);
                }catch (e) {
                    console.log('Error al aprobar ', e);
                    reject(false);
                }

            }else{
                $.notify('Se ha cancelado la transacción.', 'info');
                reject(false);
            }
        });

    });
}

function action(object, valAction){
    return new Promise((resolve, reject)=>{

        switch (valAction){
            case 1:
                $.ajax({
                    data: object,
                    url: '../controller/CRUD.controller.php?action=execute&model=HoraExtra&crud=delete',
                    type: 'post',
                    success: function (result) {
                        console.log('Resultado Aprob ', result);

                        if (isNaN(parseInt(result))) {
                            $.notify('No se elimino el registro!', 'error');
                            reject(false);
                        }

                        resolve(true);
                    }
                });
                break;
        }
    })
}

function createHE(idReporteHE) {
    return new Promise((resolve, reject)=>{
        let idHorasExtra;
        let objectHE = getHE();

        if (!Array.isArray(objectHE)){
            $.notify('Hay datos obligatorios (*) sin llenar', 'error');
            reject(false);
        }

        let he = objectHE[0];

        he.forEach(element=>{
            element.total = 0;
        });

        let data = {
            'id_reporteHE': idReporteHE,
            'HE': JSON.stringify(he)
        }

        $.ajax({
            datatype: 'JSON',
            data: { "data" : data },
            url: '../controller/CRUD.controller.php?action=insertMany&model=HoraExtra&crud=insert',
            type: 'post',
            success: function(result){

                console.log('Result-2 ', result);

                if (!result) {
                    $.notify('Error al registrar las Horas Extra.', 'error');
                    reject(false);
                }

                idHorasExtra = JSON.parse(result);

                let heValues = objectHE[1];

                for (let i = 0; i < heValues.length; i++) {
                    let values = heValues[i];
                    for (let j = 0; j < values.length; j++) {
                        values[j].id = idHorasExtra[i].id;
                    }
                }

                let data = {
                    'valuesHE': JSON.stringify(heValues)
                }

                let valuesRecargo = getDataArray(objectHE[2], idHorasExtra);

                let data1 = {
                    'valuesRecargo': JSON.stringify(valuesRecargo)
                }

                $.when( $.ajax({datatype: 'JSON', data: { "data" : data }, url: '../controller/CRUD.controller.php?action=insertMany&model=HoraExtra&crud=insertHoras', type: 'post' }), $.ajax({datatype: 'JSON', data: { "data" : data1 }, url: '../controller/CRUD.controller.php?action=insertMany&model=Recargo&crud=insert', type: 'post' }))
                    .then(function (result1, result2) {

                        if (result1[0] !== '1') {
                            $.notify('Error al registrar los valores de HE.', 'error');
                            reject(false);
                        }

                        if (result2[0] !== '1') {
                            $.notify('Error al registrar los Recargos.', 'error');
                            reject(false);
                        }

                        resolve(true);

                    })

            },
            error: function (jqXHR, textStatus, errorThrown) {
                $.notify('Error al registrar los recargos.', 'error');
                console.error(title, 'textStatus::' + textStatus, 'errorThrown:: ' + errorThrown);
            }
        });
    });
}

function changeDateReport(){
    $('#mes').change(function (){
        limitDate();
    });
}

function onLoadEditHE(){
    let htmlEdit = $('#bodyTableEdit').html();
    if (htmlEdit.length > 0){
        limitDate();
    }
}

function summaryValues() {

    var suma;
    var idField;
    var valueField;
    var valueSummary;

    $('.values').on('focus', function(e) {

        idField = $(this).data('id');

        if (!idField) {
            return;
        }

        suma = $(`#summary_${idField}`).html();
        suma = parseFloat(suma);

        valueField = $(this).val();
        
        if (valueField == '') {
            valueField = 0;
        }

        if (!isNaN(valueField)) {

            if (valueField !== '0.0' || valueField !== 0 || valueField !== '0') {
                
                suma -= parseFloat(valueField);
            }
        }
        
    });

    $('.values').on('blur', function(e) {

        valueField = $(this).val();
        suma += parseFloat(valueField);

        $(`#summary_${idField}`).html(suma);

    })
}

function clearSummaryFields() {
    $('.summariesFields').html(0);
}

function cargarLista(data, idLista){
    let html = '<option value="">Seleccione</option>';
    let datos = JSON.parse(data);

    datos.forEach(element => {
        html += `<option value="${element.id}">${element.titulo}</option>`;
    });

    $(`#${idLista}`).html(html);
}