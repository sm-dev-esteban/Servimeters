$(document).ready(function(){
    send();
    close();
});

function send(algo) {
    $('#send').click(function(e){
        e.preventDefault();

        var data = {
            'user': document.getElementById('user').value,
            'pass': document.getElementById('pass').value
        }

        $.ajax({
            data: data,
            url: './controller/session.controller.php?action=init',
            type: 'post',
            success: function(result){
                
                if (result !== '1') {
                    $.notify("Error al iniciar sesión!", 'error');
                    console.log('No se pudo conectar el servidor o credenciales incorrectas');
                    return false;
                }

                $.ajax({
                    url: './controller/session.controller.php?action=validateRole',
                    type: 'post',
                    success: function(result){
                        window.location.href='view/home';
                    }
                });     
            },
            error: function(error) {
                console.log('Error al iniciar sesión!', error);
            } 
        });
    })
}

function close(){

    $('#user').on("mouseover click", function(e) {
        $('#close').css("display", "inherit"); 
        $('#close').click(function(e){
            console.log('Hola Mundo');
            $.ajax({
                url: '../controller/session.controller.php?action=finish',
                success: function(result){
                    localStorage.clear();
                    window.location.href='../index.php';
                }
            });
        })
    });

    $('#user').mouseout(function(params) {
        $('#close').css("display", "none"); 
    });
    
}

// function test(){
//     $('#ccBtn').click(function(e){
//         e.preventDefault();

//         var titulo = $('#centro_costo').val();

//         var object = {
//             'object': {
//                 'titulo': titulo
//             }
//         }

//         $.ajax({
//             data: object,
//             url: '../controller/CRUD.controller.php?action=instance&model=CentroCosto&crud=insert',
//             type: 'post',
//             success: function(result){
//                 $('#results').html('<p>' + result + '</p>');
//             }
//         });
//     })
// }
