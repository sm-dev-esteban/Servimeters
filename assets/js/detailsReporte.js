$(document).ready(function (){
    console.log('DETAILS READY');
    clickDetails();
})

function clickDetails(){
    $('#dataTable tbody').on('click', 'td span.openDetails', function () {
        let id = $(this).data('reporte');
        $('#myModal').css('display', 'inline');
        $('#myModal').css('z-index', '999');

        let object = {
            'object': {
                'id_reporteHE': id
            }
        }

        $.when($.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=HoraExtra&crud=getHorasExtraByReport', type: 'post',}), $.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=HoraExtra&crud=getCantHorasExtraByReport', type: 'post',}), $.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=Recargo&crud=getCantRecargosByReport', type: 'post',}), $.ajax({data: object, url: './gestionHE/detalle.view.php', type: 'post',}))
            .then(function (result1, result2, result3, result4 ){
                $('#myModal').html(result4[0]);

                setEncabezadosDetails(JSON.parse(result2[0]));
                setEncabezadosDetails(JSON.parse(result3[0]));
                printDetalleHEDetails(JSON.parse(result1[0]), JSON.parse(result2[0]), JSON.parse(result3[0]));
                hideModalDetails();
            });
    })
}

function hideModalDetails() {
    $('#closeModal').on('click', function (){
        $('#myModal').html('');
        $('#myModal').css({display: 'none'});
    })
}

function printDetalleHEDetails(data1, data2, data3) {
    let html;
    let cantHE = data2.length / data1.length;
    let cantRecargo = data3.length / data1.length;
    let indiceHE = 0;
    let indiceRec = 0;

    for (let i = 0; i < data1.length; i++) {
        html += `<tr style="border-bottom: 1px solid black; font-size: 13px;">
                <td>${i+1}</td>
                <td>${data1[i].fecha}</td>
                <td>${data1[i].novedad}</td>
                <td>${data1[i].descuento}</td>`;

        for (let j = indiceHE; j < (cantHE + indiceHE); j++) {
            html += `<td>${data2[j].cantidad}</td>`;
        }

        for (let k = indiceRec; k < (cantRecargo + indiceRec); k++) {
            html += `<td>${data3[k].cantidad}</td>`;
        }

        indiceRec += cantRecargo;
        indiceHE += cantHE;
        html += `</tr>`;
    }

    $('#bodyTableDetail').append(html);
}

function setEncabezadosDetails(data){
    let titulo = [];
    let html;
    data.forEach(element=>{
        if (!titulo.includes(element.nombre)){
            titulo.push(element.nombre);
        }
    });

    titulo.forEach(element=>{
        html += `<th>${element}</th>`;
    });

    $('#headTableDetail').append(html);
}