$(document).ready(function() {
    $('#addComment').on('click', function(e) {
        // $('.comentarios').removeClass("oculto");  
        // $(".comentarios").addClass("visible");

        var comment = `
                <section class="col-12 col-4-medium col-12-xsmall">
                    <div class="animate__animated animate__fadeInUp">
                        <textarea name="comentario" id="comentario" placeholder="Ingrese texto para el comentario." rows="3" style="resize: none;" required></textarea>
                        <br>
                        <ul class="actions special">
                            <li> <button type="submit" id="sendComment" class="button primary icon solid fa-paper-plane fit">Enviar Comentario</button> </li>
                        </ul>
                    </div>
                </section>`;

        $(".comentarios").html(comment);
        addComment();

        $('#addComment').css({display: 'none'});
        $('#hideComment').css({display: 'inline'});
    });

    $('#hideComment').on('click', function(e) {
        // $('.comentarios').removeClass("visible");  
        // $(".comentarios").addClass("oculto");

        $(".comentarios").html('');

        $('#addComment').css({display: 'inline'});
        $('#hideComment').css({display: 'none'});
    });

});


function addComment() {
    $('#sendComment').on('click', function(e) {
        
        var row;
        e.preventDefault();

        var comentario = $('#comentario').val();

        if (comentario.length <= 0) {
            $(this).notify("El comentario esta vacio!", 'error');
            return false;
        }

        comentario = comentario.replaceAll('script', '');

        var creador = $('#usuarioLogin').html();
        var fecha = new Date();
        fecha = fecha.getFullYear() + '-' + (fecha.getMonth() + 1) + '-' + fecha.getDate();
        var idReporteHE = $('#idReporteHE').data('id');

        var object = {
            'object': {
                'creadoPor': creador,
                'fecha': fecha,
                'idReporteHE': idReporteHE,
                'cuerpo': comentario
            }
        }
        
        $.ajax({
            data:  object,
            url: '../controller/CRUD.controller.php?action=execute&model=Comentario&crud=insert',
            type: 'post',
            beforeSend: function() {
                $('#sendComment').css({display: 'none'});
            },
            success: function(result){
                
                if (isNaN(parseInt(result))) {
                    $.notify('No se envió el comentario', 'error');
                    return false;
                }

                row = `<tr>
                <td style="text-align: left" id="comment-${result}">${creador} - ${fecha}</td>
                </tr>
                <tr>
                <td>${comentario}</td>
                </tr>`;
                
                $('#bodyComments').append(row);
                
                $('#comentario').val('');
                
                $.notify('Comentario enviado', 'success');
                $('#sendComment').css({display: 'inline'});

                let correoAprobador = localStorage.getItem('correoAprobador');
                let correoEmpleado = $('#cc').data('correoempleado');

                var data = {
                    'to': correoAprobador,
                    'from': correoEmpleado,
                    'empleado': creador,
                    'idHE': idReporteHE,
                    'novedad': comentario
                }

                $.ajax({
                    data:  data,
                    url: '../controller/Email.controller.php?email=reporteNovedad',
                    type: 'post',
                    success: function(result){

                        if (result == 1) {
                            $.notify('Notificación enviada', 'success');
                            return true;
                        }

                        $.notify('No se envió la notificación', 'error');
                    }

                });

                return true;

            }

        });

    })
}