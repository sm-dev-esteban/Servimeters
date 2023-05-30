'use strict';
$(document).ready(function () {

    $.when($.ajax('../controller/CRUD.controller.php?action=listAll&model=CentroCosto&crud=get'), $.ajax('../controller/CRUD.controller.php?action=listAll&model=Clase&crud=get'))
        .then(function (result, result2) {

            let htmlClase = '';
            let data2 = JSON.parse(result2[0]);
            data2.forEach(element=>{
               htmlClase += `<option value="${element.id}">${element.titulo}</option>`;
            });
            $('#clase').html(htmlClase);

            let html = '';
            let data = JSON.parse(result[0]);
            data.forEach(element=>{
                html += `<tr>
                         <td><input type="text" class="fieldEdit" name="" id="ceco_${element.id}" value="${element.titulo}" style="font-size: 12px;" required></td>
                         <td>
                            <select name="clase" id="clase_${element.id}">
                                ${htmlClase}
                            </select>
                         </td>
                         <td> <span data-id="${element.id}" class="updateCeco icon solid fa-check-circle fi saveAdmin" style="color: #3c763d;"></span> </td>
                        </tr>`;
            });

            $('#ceco').html(html);
            data.forEach(element=>{
                $(`#clase_${element.id} option[value=${element.id_clase}]`).attr("selected",true);
            });

            updateCECO();
            createCECO();
        })
});

function updateCECO() {
    $('.updateCeco').click(function () {
        swal('¿Desea actualizar el registro?', {
            buttons: ["No!", "Si!"],
        }).then(async (val)=>{
            if (val){
                let id = $(this).data('id');
                let titulo = $(`#ceco_${id}`).val();
                let clase = $(`#clase_${id}`).find(':selected').val();

                let object = {
                    'object': {
                        'id': id,
                        'titulo': titulo,
                        'clase': clase
                    }
                }

                console.log(object);

                $.when($.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=CentroCosto&crud=update', type: 'post'}))
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
function createCECO() {
    $('#sendData').click(function (e) {
        e.preventDefault();
        swal('¿Desea crear el registro?', {
            buttons: ["No!", "Si!"],
        }).then(async (val)=>{
            if (val){
                let titulo = $(`#formCeco input[name="title"]`).val();
                let clase = $('#clase').find(':selected').val();

                let object = {
                    'object': {
                        'titulo': titulo,
                        'clase': clase
                    }
                }

                $.when($.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=CentroCosto&crud=insert', type: 'post'}))
                    .then(function (result){
                        console.log(result);
                        if (!result){
                            $.notify('Error al crear', 'error');
                            return;
                        }

                        $.notify('Creado con exito!', 'success');
                        reloadPageAdmin();
                    })

            }else{
                $.notify('Se ha cancelado la transacción.', 'info');
            }
        });
    })
}

function reloadPageAdmin() {
    $.when($.ajax('./admin/centroCosto.view.php'))
        .then(function(result) {
            var script = "<script src=\"../assets/js/admin/cecoAdmin.js\"></script>";

            //Cargar HTML
            $('#links').append(script);
            $('#result').html(result);
        })
}