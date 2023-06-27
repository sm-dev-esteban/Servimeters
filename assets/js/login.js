$(document).ready(function () {
    $(`#login`).on(`submit`, function (e) {
        e.preventDefault();

        var data = {
            'user': document.getElementById('user').value,
            'pass': document.getElementById('pass').value
        };

        $.ajax(`./controller/session.controller.php?action=init`, {
            // data: new FormData(this),
            data: data,
            type: 'post',
            dataType: "json",
            success: function (result) {
                console.log(result);
                if (result.status == false) {
                    $.notify("Error al iniciar sesión!", 'error');
                } else if (result.status == true) {
                    window.location.href = 'view/home';
                }
            },
            error: function () {
                $.notify("Error al iniciar sesión!", 'error');
            }
        });
    });
});

function validateRol() {
}