let config;
$(document).ready(function(e) {
    // removeClass();
    // reportar();
    // estado();
    // gestionar();
    // gestionRH();
    // adminCECO();
    // adminAprobadores();
});

// function reportar(){
//     $('#reportar').click(function(e) {
//         //e.preventDefault();
//         var reportar = $(this);
//         reportar.css('pointer-events', 'none');
//         var script = "<script src=\"../assets/js/reporteHE.js\"></script>";
//         var style = "<link rel='stylesheet' href=\"../assets/css/load.css\"></link>";

//         $.when($.ajax('./reportar/index.view.php'))
//             .then(function (result1) {
//                 // Cargar HTML Reporte
//                 $('#links').append(script, style);
                
//                 reportar.css('pointer-events', 'auto');
//                 reportar.addClass('menuSelect');

//                 $('#result').html(result1);
//             });
//     });
// }

// function estado() {
//     $('#estado').click(function(e) {
//         e.preventDefault();

//         var estado = $(this);
//         estado.css('pointer-events', 'none');
//         var script = "<script src=\"../assets/js/listadoHE.js\"></script>";
//         var script2 = "<script src=\"../assets/js/detailsReporte.js\"></script>";

//         $.when($.ajax('./estado/listEstado.view.php'))
//             .then(function(result1) {
                
//                 //Cargar HTML
//                 $('#links').append(script2);
//                 $('#links').append(script);

//                 estado.css('pointer-events', 'auto');
//                 estado.addClass('menuSelect');
//                 $('#result').html(result1);
//                 $(this).prop('disabled', false);

//             })

//     });
// }

// function gestionar() {
//     $('#gestionar').click(function(e) {
//         e.preventDefault();

//         var gestionar = $(this);
//         gestionar.css('pointer-events', 'none');
//         var script = "<script src=\"../assets/js/aproveRejectHE.js\"></script>";
//         var script2 = "<script src=\"../assets/js/detailsReporte.js\"></script>";

//         $.when($.ajax('./gestionHE/gestionar.view.php'))
//             .then(function(result) {

//                 //Cargar HTML
//                 $('#links').append(script);
//                 $('#links').append(script2);
//                 gestionar.addClass('menuSelect');
//                 gestionar.css('pointer-events', 'auto');
//                 $('#result').html(result);
//                 $(this).prop('disabled', false);
//                 $('#typeGestion').attr('data-type', 'gestionJefesGerentes');

//             })

//     });
// }

// function gestionRH() {
//     $('#gestionarRH').click(function(e) {
//         e.preventDefault();

//         var gestionContable = $(this);
//         gestionContable.css('pointer-events', 'none');
//         var script = "<script src=\"../assets/js/aproveRejectHE.js\"></script>";
//         var script2 = "<script src=\"../assets/js/detailsReporte.js\"></script>";

//         $.when($.ajax('./gestionHE/gestionar.view.php'))
//             .then(function(result) {

//                 //Cargar HTML
//                 $('#links').append(script);
//                 $('#links').append(script2);
//                 gestionContable.addClass('menuSelect');
//                 gestionContable.css('pointer-events', 'auto');

//                 $('#result').html(result);
//                 $(this).prop('disabled', false);

//                 $('#typeGestion').attr('data-type', 'gestionRH');

//             })
//     });
// }

// function gestionContable(e) {
//     //$('#gestionarContable').click(function(e) {
//         e.preventDefault();

//         var gestionContable = $('#mainContable');
//         gestionContable.css('pointer-events', 'none');
//         var script = "<script src=\"../assets/js/aproveRejectHE.js\"></script>";
//         var script2 = "<script src=\"../assets/js/detailsReporte.js\"></script>";

//         $.when($.ajax('./gestionHE/gestionar.view.php'))
//             .then(function(result) {

//                 //Cargar HTML
//                 $('#links').append(script);
//                 $('#links').append(script2);
//                 gestionContable.addClass('menuSelect');
//                 gestionContable.css('pointer-events', 'auto');
//                 $('#result').html(result);
//                 $(this).prop('disabled', false);

//                 $('#typeGestion').attr('data-type', 'gestionContable');

//             })
//     //});
// }

// function reporte(e){
//     e.preventDefault();
//     $(this).prop('disabled', true);

//     var gestionContable = $('#mainContable');
//     gestionContable.css('pointer-events', 'none');
//     var script = "<script src=\"../assets/js/generarReporte.js\"></script>";

//     $.when($.ajax('./reporte/index.view.php'))
//         .then(function(result) {

//             //Cargar HTML
//             $('#links').append(script);
//             gestionContable.addClass('menuSelect');
//             gestionContable.css('pointer-events', 'auto');
//             $('#result').html(result);
//             $(this).prop('disabled', false);
//         })
// }

// function adminClase(e) {
//     e.preventDefault();
//     $(this).prop('disabled', true);

//     var adminCeco = $('#admin');
//     adminCeco.css('pointer-events', 'none');
//     var script = "<script src=\"../assets/js/admin/claseAdmin.js\"></script>";

//     $.when($.ajax('./admin/clase.view.php'))
//         .then(function(result) {

//             //Cargar HTML
//             $('#links').append(script);
//             adminCeco.addClass('menuSelect');
//             adminCeco.css('pointer-events', 'auto');
//             $('#result').html(result);
//             $(this).prop('disabled', false);
//         })
// }

// function adminCECO(e) {
//     e.preventDefault();
//     $(this).prop('disabled', true);

//     var adminCeco = $('#admin');
//     adminCeco.css('pointer-events', 'none');
//     var script = "<script src=\"../assets/js/admin/cecoAdmin.js\"></script>";

//     $.when($.ajax('./admin/centroCosto.view.php'))
//         .then(function(result) {

//             //Cargar HTML
//             $('#links').append(script);
//             adminCeco.addClass('menuSelect');
//             adminCeco.css('pointer-events', 'auto');
//             $('#result').html(result);
//             $(this).prop('disabled', false);
//         })
// }

// function adminAprobadores(e) {
//     e.preventDefault();
//     $(this).prop('disabled', true);

//     var adminCeco = $('#admin');
//     adminCeco.css('pointer-events', 'none');
//     var script = "<script src=\"../assets/js/admin/aprobadoresAdmin.js\"></script>";

//     $.when($.ajax('./admin/Aprobadores.view.php'))
//         .then(function(result) {

//             //Cargar HTML
//             $('#links').append(script);
//             adminCeco.addClass('menuSelect');
//             adminCeco.css('pointer-events', 'auto');
//             $('#result').html(result);
//             $(this).prop('disabled', false);
//         })
// }

$(document).ready(function () {
    // $(`#header a.menuItem, #navPanel a.link`).each(function () {
    $(`#header a[href!="#"], #navPanel a[href!="#"]`).each(function () {
        $(this).on("click", function (e) {
            e.preventDefault();

            $(`.menuSelect`).each(function () {
                $(this).removeClass('menuSelect');
            });

            $(this).addClass('menuSelect');

            let
            src = $(this).attr("href"),
            scr = $(this).attr("data-script"); // los de la etiqueta

            $.ajax('../controller/Content.controller.php', {
                // async: true,
                dataType: "HTML",
                type: "POST",
                data: {
                    view: src ? src : false
                },
                beforeSend: function() {}, // por si depronto quieren poner una animacion mientras carga el contenido
                success: function (result) {
                    // nota Esteban: Si tengo tiempo voy hacer un controlador de php que escriba todos los archivos de .js en un .json para que no se tan manual, pero de momento dejo la idea para hacerlo luego
                    let scripts = [ // todos los scripts
                        "../assets/js/reporteHE.js",
                        "../assets/js/listadoHE.js",
                        "../assets/js/detailsReporte.js",
                        "../assets/js/aproveRejectHE.js",
                        "../assets/js/generarReporte.js",
                        "../assets/js/admin/claseAdmin.js",
                        "../assets/js/admin/cecoAdmin.js",
                        "../assets/js/admin/aprobadoresAdmin.js"
                    ];

                    let loadScript = []; // arreglo que va a contener los scripts que se van a cargar en la pagina

                    scr.split(",").forEach(q => { // hago un recorrido del atributo que agregue a la lista
                        loadScript.push(scripts.filter( // lo carga al arreglo
                            w => w.toLowerCase().includes(q.toLowerCase().replace(" ", "")) // filtro por los valores de los atributos
                        ));
                    });

                    for (ls in loadScript) { // por ultimo hago que jquery me cargue esos scripts
                        $.getScript(loadScript[ls]);
                    }

                    $('#result').html(result); // cargo el contenido de la pagina
                },
                complete: function() {} // por si depronto quieren poner algo despues de cargar el contenido
            });
        })
    });
});

// function removeClass() {
//     $('li').on('click', function() {
//         $(".menuSelect").removeClass("menuSelect");
//     })
// }