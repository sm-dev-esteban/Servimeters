// class System {

// }
const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
const language = Intl.DateTimeFormat().resolvedOptions().locale.split("-")[0];
const datatableParams = {
    "responsive": true, "lengthChange": true, "autoWidth": false, "dom": "Bfrtip",
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
    "language": {
        "lengthMenu": "mostrar _MENU_ entradas",
        "zeroRecords": "No conseguimos ningún resultado",
        "info": "Mostrando _PAGE_ de _PAGES_",
        "infoFiltered": "(filtrado _MAX_ registros totales)",
        "search": "Buscar",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "emptyTable": "Sin resultados para mostrar",
        "infoEmpty": "Sin resultados para mostrar",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    }
};
$("html").attr("lang", language);
//------------------------------------------------------------------------------------------------------------------------------------------------------
// config con async await
//------------------------------------------------------------------------------------------------------------------------------------------------------
const loadConfig = async function () {
    let phpLoadConfig = await fetch(`${location.origin}/${location.pathname.split("/")[1]}/config/config.php`);
    let jsonLoadConfig = await fetch(`${location.origin}/${location.pathname.split("/")[1]}/config/config.json`);
    return await jsonLoadConfig.json();
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Replace estricto que reemplaza en absoluto lo que quieran reemplazar ( jaja perdon por la redundancia, pero si e asi n: )
// Nota: función peligrosa ojo en como se usa
//------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * @info  Este es un replace que borra en absoluto lo que le pasen
*/
function strictReplace(string, search, replace) {
    if (!string.includes(search) || search == replace) { // valido si esta lo que quieren reemplazar y que no sea igual lo que busca con lo que quiere reemplazar
        return string; // si no esta pa fuera (sale por que no hay nada que reemplazar)
    } else { // si esta lo que quieren reemplzar pasa a esta validación
        string = string.replaceAll(search, replace); // replace normal de toda la vida
        if (string.includes(search)) { // aqui viene el truco, valido si todavia tiene lo que quieren reemplazar
            string = strictReplace(string, search, replace); // genero un bucle con el cual reenvio el nuevo string
        }
        return string; // si termina pa fuera (sale por que ya reemplazo todo)
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// alertas personalizables
// Nota: Por lo menos esa es mi idea :c
//------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * @param Array arrayAlert arreglo con valores para la alerta ninguno es oblitagorio ejemplo alerts([title: "prueba", text: "test", icon: "success", position: "top-end"])
 * @param String arrayAlert[position] valores que acepta (top, top-start, top-end, center, center-start, center-end, bottom, bottom-start, bottom-end) default top-end
 * @param String arrayAlert[icon] valores que acepta (success, error, warning, info, question) default false
 * @param String typeAlert tipo de alerta segun la que quieran usar por defecto Sweetalert2
*/
function alerts(arrayAlert, typeAlert = "Sweetalert2") {

    // la configuracion la hice basandome en los valores de Sweetalert2 para nuevas alertas seria adecuarlas para que funcione con estos parametros
    let config = $.extend({
        title: false, // titulo
        text: false, // texto
        icon: false, // icon
        duration: 3000,
        position: "top-end" // por si depronto quieren configurar una posicion diferente
    }, arrayAlert);

    switch (typeAlert.toLocaleLowerCase()) { // por si quieren hacer configuracion diferentes de alertas yo de momento voy a utilizar esta con sweetalert
        case "sweetalert2":
            let Sweetalert2 = Swal.mixin({
                toast: true,
                position: config.position,
                showConfirmButton: false,
                timer: config.duration,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer)
                    toast.addEventListener("mouseleave", Swal.resumeTimer)
                }
            });

            Sweetalert2.fire({
                title: `${config.title ? config.title : ``}`,
                text: `${config.text ? config.text : ``}`,
                icon: `${config.icon ? config.icon : ``}`
            })
            break;
        default:
            window.alert(`
                ${config.status ? config.status : ``}
                ${config.title ? config.title : ``}
                ${config.text ? config.text : ``}
            `);
            break;
    }
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// carga una lista de opciones
//------------------------------------------------------------------------------------------------------------------------------------------------------
function cargarLista(data, ident, idvalue, content) {
    let html = '<option value="">Seleccione</option>';
    // let datos = JSON.parse(data);
    let datos = data;

    datos.forEach(element => {
        html += `<option value="${element[idvalue]}">${element[content]}</option>`;
    });

    $(ident).html(html);
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// imprime documentos en una ventana independiente
//------------------------------------------------------------------------------------------------------------------------------------------------------
function wPrint(ident) {
    // let windowPrint = window.open('', 'PRINT', 'fullscreen');
    let windowPrint = window.open('', 'PRINT', 'width=10,height=10,top=200,left=200,toolbars=no,scrollbars=no,status=no,resizable=no');

    windowPrint.document.write(`<!DOCTYPE html>`);
    windowPrint.document.write(`<html lang="${language}">`);

    windowPrint.document.write(`<head>`);
    windowPrint.document.write(`<meta charset="UTF-8">`);
    windowPrint.document.write(`<meta http-equiv="X-UA-Compatible" content="IE=edge">`);
    windowPrint.document.write(`<meta name="viewport" content="width=device-width, initial-scale=1.0">`);
    windowPrint.document.write(`<title>${$("html title").text()}</title>`);
    windowPrint.document.write(`<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">`);
    windowPrint.document.write(`</head>`);

    windowPrint.document.write(`<body>`);
    windowPrint.document.write(`<div class="container mt-5">`);
    windowPrint.document.write($(ident).html());
    windowPrint.document.write(`</div>`);
    windowPrint.document.write(`</body>`);

    windowPrint.document.write(`<foot>`);
    windowPrint.document.write(`<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>`);
    windowPrint.document.write(`<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>`);
    windowPrint.document.write(`</foot>`);

    windowPrint.document.write(`
    <script>
    $(document).ready(function () {
        this.focus();
        this.print();
        this.close();
    });
    </script>
    `);

    windowPrint.document.write(`</html>`);
    windowPrint.document.close();
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// accede a las funciones static de automaticForm
//------------------------------------------------------------------------------------------------------------------------------------------------------
function automaticForm(action, params) {

    let resp = $.ajax(`../controller/af.controller.php?action=${action}`, {
        type: "POST",
        data: { "param": params },
        dataType: "JSON",
        async: false
    });

    return resp.responseJSON;
}