'use strict';
$(document).ready(function () {
    console.log('Cargando admin Aprob');

    $.when($.ajax('../controller/CRUD.controller.php?action=listAll&model=Aprobador&crud=get'))
        .then(function (result) {
            let html = '';
            let data = JSON.parse(result);
            data.forEach(element=>{
                html += `<tr>
                         <td><input type="text" class="fieldEdit" name="" id="name_${element.id}" value="${element.nombre}" style="font-size: 12px;" required></td>
                         <td><input type="email" class="fieldEdit" name="" id="email_${element.id}" value="${element.correo}" style="font-size: 12px;" required></td>
                         <td> <select name="tipo" id="tipo_${element.id}" style="font-size: 12px;">
                                <option value="NA" ${element.tipo === 'NA' ? 'selected="selected"' : ''}>NA</option>
                                <option value="Jefe" ${element.tipo === 'Jefe' ? 'selected="selected"' : ''}>Jefe</option>
                                <option value="Gerente" ${element.tipo === 'Gerente' ? 'selected="selected"' : ''}>Gerente</option>                        
                            </select> 
                        </td>
                         <td>
                            <select name="gestiona" id="gestiona_${element.id}" style="font-size: 12px;">
                                <option value="NA" ${element.gestiona === 'NA' ? 'selected="selected"' : ''}>NA</option>
                                <option value="Contable" ${element.gestiona === 'Contable' ? 'selected="selected"' : ''}>Contable</option>
                                <option value="RH" ${element.gestiona === 'RH' ? 'selected="selected"' : ''}>RH</option>                        
                            </select> 
                        </td>
                        <td>
                            <select name="isadmin" id="isadmin_${element.id}" style="font-size: 12px;">
                                <option value="No">No</option>
                                <option value="Si" ${element.esAdmin === 'Si' ? 'selected="selected"' : ''}>Si</option>                        
                            </select> 
                        </td>
                         <td> <span data-id="${element.id}" class="updateAprobador icon solid fa-check-circle fi saveAdmin"></span> </td>
                        </tr>`;
            });

            $('#aprobadores').html(html);
            updateAprobador();
            createAprobador();
        })
});

function updateAprobador() {
    $('.updateAprobador').click(function () {
        console.log('update');
        swal('¿Desea actualizar el registro?', {
            buttons: ["No!", "Si!"],
        }).then(async (val)=>{
            if (val){
                let id = $(this).data('id');
                let nombre = $(`#name_${id}`).val();
                let correo = $(`#email_${id}`).val();
                let tipo = $(`#tipo_${id}`).find(':selected').val();
                let gestiona = $(`#gestiona_${id}`).find(':selected').val();
                let esAdmin = $(`#isadmin_${id}`).find(':selected').val();

                let object = {
                    'object': {
                        'id': id,
                        'nombre': nombre,
                        'correo': correo,
                        'tipo': tipo,
                        'gestiona': gestiona,
                        'esAdmin': esAdmin
                    }
                }

                console.log(object);

                $.when($.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=Aprobador&crud=update', type: 'post'}))
                    .then(function (result){
                        console.log(result);
                        if (!result){
                            $.notify('Error al actualizar', 'error');
                            return;
                        }

                        $.notify('Actualizado con exito!', 'success');
                    })

            }else{
                $.notify('Se ha cancelado la transacción.', 'info');
            }
        });

    })
}
function createAprobador() {
    $('#sendDataAprob').click(function (e) {
        e.preventDefault();
        console.log('crear');
        swal('¿Desea crear el registro?', {
            buttons: ["No!", "Si!"],
        }).then(async (val)=>{
            if (val){
                let nombre = $(`#formAprob input[name="name"]`).val();
                let correo = $(`#formAprob input[name="email"]`).val();
                let tipo = $(`#tipo`).find(':selected').val();
                let gestiona = $(`#gestiona`).find(':selected').val();
                let esAdmin = $(`#isadmin`).find(':selected').val();

                let object = {
                    'object': {
                        'nombre': nombre,
                        'correo': correo,
                        'tipo': tipo,
                        'gestiona': gestiona,
                        'esAdmin': esAdmin
                    }
                }

                console.log(object);

                $.when($.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=Aprobador&crud=insert', type: 'post'}))
                    .then(function (result){
                        console.log(result);
                        if (!result){
                            $.notify('Error al actualizar', 'error');
                            return;
                        }

                        $.notify('Actualizado con exito!', 'success');
                        reloadPageAdminAprob();
                    })

            }else{
                $.notify('Se ha cancelado la transacción.', 'info');
            }
        });
    })
}

function reloadPageAdminAprob() {
    $.when($.ajax('./admin/Aprobadores.view.php'))
        .then(function(result) {
            var script = "<script src=\"../assets/js/admin/aprobadoresAdmin.js\"></script>";

            //Cargar HTML
            $('#links').append(script);
            $('#result').html(result);
        })
}