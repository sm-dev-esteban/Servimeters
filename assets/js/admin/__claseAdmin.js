'use strict';
$(document).ready(function () {

    $.when($.ajax('../controller/CRUD.controller.php?action=listAll&model=Clase&crud=get'))
        .then(function (result) {
            let html = '';
            let data = JSON.parse(result);
            data.forEach(element=>{
                html += `<tr>
                         <td><input type="text" class="fieldEdit" name="" id="clase_${element.id}" value="${element.titulo}" style="font-size: 12px;" required></td>
                         <td> <span data-id="${element.id}" class="updateClase icon solid fa-check-circle fi saveAdmin" style="color: #3c763d;"></span> </td>
                        </tr>`;
            });

            $('#clase').html(html);
            updateClase();
            createClase();
        })
});

function updateClase() {
    $('.updateClase').click(function () {
        swal('¿Desea actualizar el registro?', {
            buttons: ["No!", "Si!"],
        }).then(async (val)=>{
            if (val){
                let id = $(this).data('id');
                let titulo = $(`#clase_${id}`).val();

                let object = {
                    'object': {
                        'id': id,
                        'titulo': titulo
                    }
                }

                $.when($.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=Clase&crud=update', type: 'post'}))
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
function createClase() {
    $('#sendData').click(function (e) {
        e.preventDefault();
        swal('¿Desea crear el registro?', {
            buttons: ["No!", "Si!"],
        }).then(async (val)=>{
            if (val){
                let titulo = $(`#formClase input[name="title"]`).val();

                let object = {
                    'object': {
                        'titulo': titulo
                    }
                }

                console.log(object);

                $.when($.ajax({data: object, url: '../controller/CRUD.controller.php?action=execute&model=Clase&crud=insert', type: 'post'}))
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
    $.when($.ajax('./admin/clase.view.php'))
        .then(function(result) {
            var script = "<script src=\"../assets/js/admin/claseAdmin.js\"></script>";

            //Cargar HTML
            $('#links').append(script);
            $('#result').html(result);
        })
}