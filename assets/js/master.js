// class System {

// }
const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
const locale = Intl.DateTimeFormat().resolvedOptions().locale;
const language = locale.split("-")[0];
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
    },
    initComplete: function () {
        this.api().columns().every(function () {
            var $this = this;
            $('input', this.footer()).on('keyup change clear', function () {
                if ($this.search() !== this.value) {
                    $this.search(this.value).draw();
                }
            });
        });
    }
};
$("html").attr("lang", language);
//------------------------------------------------------------------------------------------------------------------------------------------------------
// config con async await
//------------------------------------------------------------------------------------------------------------------------------------------------------
const loadConfig = async function () {
    let phpLoadConfig = await fetch(`${location.origin}/${location.pathname.split("/")[1]}/config.php`);
    let jsonLoadConfig = await fetch(`${location.origin}/${location.pathname.split("/")[1]}/config/config.json`);
    return await jsonLoadConfig.json();
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Plugins
// esta zona es para cargar cualquier plugin que creen
//------------------------------------------------------------------------------------------------------------------------------------------------------
$(document).ready(function () {
    // sugerencia si el plugin esta listo pasenlo a .min
    let plugins = [
        "../assets/js/plugins/selectMaster/selectMaster.min.js",
        "../assets/js/plugins/createDropzone/createDropzone.min.js"
    ];
    for (src in plugins) { // por ultimo hago que jquery me cargue esos scripts
        if (plugins[src].length) {
            $.getScript(plugins[src]);
        }
    }
});
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Replace estricto que reemplaza en absoluto lo que quieran reemplazar ( jaja perdon por la redundancia, pero si e asi n: )
// Nota: función peligrosa ojo en como se usa
//------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * @param {string} string cadena de texto
 * @param {string} search valor que van a buscar
 * @param {string} replace valor con el que lo van a reemplazar
 * @return retorna una nueva cadena
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
 * @param {Array} arrayAlert arreglo con valores para la alerta ninguno es oblitagorio ejemplo alerts([title: "prueba", text: "test", icon: "success", position: "top-end"])
 * @param {String} arrayAlert[position] valores que acepta (top, top-start, top-end, center, center-start, center-end, bottom, bottom-start, bottom-end) default top-end
 * @param {String} arrayAlert[icon] valores que acepta (success, error, warning, info, question) default false
 * @param {String} typeAlert tipo de alerta segun la que quieran usar por defecto Sweetalert2
*/

$(window).on("focus", function () {
    automaticForm("updateSession", [{ "windowIsFocus": true }]);
}).on("blur", function () {
    automaticForm("updateSession", [{ "windowIsFocus": false }]);
});

function alerts(arrayAlert, typeAlert = "Sweetalert2") {
    let windowIsFocus = (automaticForm("getSession", ["windowIsFocus"]) === "true");
    // la configuracion la hice basandome en los valores de Sweetalert2 para nuevas alertas seria adecuarlas para que funcione con estos parametros
    let config = $.extend({
        title: ``, // titulo
        text: ``, // texto
        html: ``, // html
        icon: ``, // icon
        duration: 3000,
        position: "top-end" // por si depronto quieren configurar una posicion diferente
    }, arrayAlert);

    if (config.icon) config.icon = config.icon.toLocaleLowerCase();

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
                title: config.title,
                text: config.text,
                icon: config.icon,
                html: config.html
            });
            if (!windowIsFocus) alerts(config, "window");
            break;
        case "window":
            if (!windowIsFocus && "Notification" in window) {
                notification = false;
                if (Notification.permission === "granted") {
                    notification = new Notification(config.title, {
                        body: config.text,
                        icon: $(`link[rel="icon"]`).attr("href"),
                        dir: "ltr"
                    });
                } else if (Notification.permission !== "denied") {
                    Notification.requestPermission().then((permission) => {
                        if (permission === "granted") {
                            notification = new Notification(config.title, {
                                body: config.text,
                                icon: $(`link[rel="icon"]`).attr("href"),
                                dir: "ltr"
                            });
                        }
                    });
                }
                if (false !== notification) {
                    $(notification).on("click", function (e) {
                        e.preventDefault();
                        window.open(location.href, "_blank");
                    });
                }
            }
            break;
        default:
            window.alert(`
                ${config.status}
                ${config.title}
                ${config.text}
                ${config.html}
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
    let windowPrint = window.open('', 'PRINT', 'fullscreen');

    windowPrint.document.write(`<!DOCTYPE html>`);
    windowPrint.document.write(`<html lang="${language}">`);

    windowPrint.document.write(`<head>`);
    windowPrint.document.write(`<meta charset="UTF-8">`);
    windowPrint.document.write(`<meta http-equiv="X-UA-Compatible" content="IE=edge">`);
    windowPrint.document.write(`<meta name="viewport" content="width=device-width, initial-scale=1.0">`);
    windowPrint.document.write(`<title>${$("html title").text()}</title>`);
    // windowPrint.document.write(`<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">`);
    // estaba sin internet y me di cuenta que es mejor usar los estilos de la plantilla para prevenir
    let links = [];

    $(`head link[rel="stylesheet"]`).each(function () {
        links.push($(this).attr("href"));
    });

    links = links.filter(
        x => x.search("http") == -1 ? true : false
    ); // quito cualquier link de pagina externa

    origin = location.origin;
    pathna = location.pathname.split("/")[1];
    folder = `${origin}/${pathna}/`;

    links = links.map(
        x => x.replace("../", folder)
    ); // cambio la ruta para que este completa

    for (data in links) {
        windowPrint.document.write(`<link rel="stylesheet" href="${links[data]}">`);
    }
    windowPrint.document.write(`</head>`);

    windowPrint.document.write(`<body>`);
    windowPrint.document.write(`<div class="container mt-5">`);
    windowPrint.document.write($(ident).html());
    windowPrint.document.write(`</div>`);
    windowPrint.document.write(`</body>`);

    windowPrint.document.write(`<foot>`);
    // windowPrint.document.write(`<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>`);
    // windowPrint.document.write(`<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>`);
    windowPrint.document.write(`<script src="${folder}AdminLTE/plugins/jquery/jquery.min.js"></script>`);
    windowPrint.document.write(`</foot>`);

    windowPrint.document.write(`
    <script>
    $(document).ready(function () {
        window.focus();
        window.print();
        window.close();
    }).on("click", function() {
        window.print();
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
//------------------------------------------------------------------------------------------------------------------------------------------------------
// crea o edita elementos
//------------------------------------------------------------------------------------------------------------------------------------------------------
function isHTML(str) {
    return str instanceof Element || str instanceof Document;
}

/**
 * @param {String|html} tag nombre de la etiqueta a crear o tambien recive una etiqueta y se puede editar
 * @param {String[]} [attrs=null] arreglo con los atributos de la etiqueta tambien
 * @returns {html} Retorna la nueva etiqueta con sus nuevos valores
*/
function createElem(tag, attrs = null) {
    let newElement = (!isHTML(tag) ? document.createElement(tag) : tag); // valido si es un elemento html, si no es creo lo que envie y si no solo se asigna a la variable
    if (null !== attrs) {
        for (data in attrs) {
            if ([`text`].includes(data)) {
                newElement.textContent = attrs[data]; // html
            } else {
                newElement.setAttribute(data, attrs[data]); // atributos
            }
        }
    }
    return newElement;
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Envia Correos
//------------------------------------------------------------------------------------------------------------------------------------------------------
/**
 * @param {string|string[]} to Correo al que se le va a enviar
 * @param {string} cc Relacionados
 * @param {string} subject Titulo
 * @param {string} body Contenido
 * @return {string[]} Retorna un arreglo con un estado o un error dado el caso
 * @description Envía correos a uno o múltiples correos a la vez. Mientras se envía, muestra una pequeña animación para que no lo molesten.
*/
function sendMail(to, cc, subject, body) {
    $div = $(`.wrapper .preloader`);
    $img = $(`.wrapper .preloader img`);

    $img_src = $img.attr("src");
    $img_class = $img.attr("class");

    $div_class = $div.attr("class");

    $justifyContent = ["start", "end", "center", "between", "around"];
    $alignItems = ["start", "end", "center", "baseline", "stretch"];

    $.ajax(`../controller/Email.controller.php?email=default`, {
        dataType: "JSON",
        type: "POST",
        data: {
            to: to, /* Array|String */
            cc: cc, /* String */
            subject: subject, /* String */
            body: body /* String */
        },
        async: false,
        beforeSend: function () { // Los correos son pesados para enviar así que cargo una animación pa que no se aburra
            $div.css({
                "height": `100%`
            });

            $interval = setInterval(() => {
                $rJC = Math.floor(Math.random() * $justifyContent.length);
                $rAI = Math.floor(Math.random() * $alignItems.length);
                $div.attr("class", `preloader flex-column justify-content-${$justifyContent[$rJC]} align-items-${$alignItems[$rAI]}`);
            }, 2500);

            $img.removeAttr("class").attr("src", "../images/email.png").css({
                "display": `block`,
                "animation": `wobble 2500ms infinite`
            });
        },
        success: function (response) {
            if (!response["status"]) {
                alerts({
                    title: `Error al enviar el correo`,
                    icon: `error`,
                    duration: 10000
                })
            } else {
                alerts({
                    title: `Correo Enviado`,
                    icon: `success`
                })
            }
        },
        complete: function () { // Los correos son pesados para enviar así que cargo una animación pa que no se aburra
            window.clearInterval($interval); // destruye el interval
            $div.attr("class", $div_class).css({
                "height": `0`
            });
            $img.attr("class", $img_class).attr("src", $img_src).css({
                "display": `none`
            });
        }
    });
}
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Excel xls
//------------------------------------------------------------------------------------------------------------------------------------------------------
function xls(identifier, config = {}) {
    let fecha, params;
    fecha = new Date().toLocaleDateString(locale, { weekday: "long", year: "numeric", day: "numeric", month: "2-digit" });
    fecha = fecha.replace(",", "");

    let $this, $innerHTML, $tagName;
    $this = $($(identifier).get(0));
    if (!$this || $this.length == 0) {
        alerts({
            title: `Error al descargar el archivo XLS. Indicador no encontrado -> ${identifier}`,
            icon: `error`
        });
    } else {
        $innerHTML = $this.html();
        $tagName = $this.prop(`tagName`);

        params = $.extend({
            title: `@now`,
            filename: `@now`,
            // checkTable: true
        }, config);

        Object.keys(params).forEach(x => {
            if (typeof params[x] === "string") {
                params[x] = params[x].replace("@now", fecha);
            }
        });

        noValidFn = {
            " ": "_",
            ",": "_"
        };

        for (data in noValidFn) {
            search = data;
            replace = noValidFn[data];
            params.filename = params.filename.replaceAll(search, replace);
        }

        noValidCo = [
            /<input(.*?)>/g
        ];

        for (data in noValidCo) {
            $innerHTML = $innerHTML.replace(noValidCo[data], "");
        }

        if (!params.filename.includes(`.xls`))
            params.filename = `${params.filename}.xls`;


        if (params.checkTable === true ? $tagName == `TABLE` : true) {
            let data, http;
            data = JSON.stringify({ param: params, content: $innerHTML });
            http = new XMLHttpRequest();

            http.open(`POST`, `../controller/Excel.controller.php`);
            http.onload = () => {
                if (http.status === 200) {
                    let blob, url, a;
                    blob = new Blob([http.response], { type: `application/vnd.ms-excel` })
                    url = URL.createObjectURL(blob);
                    a = createElem(`a`, {
                        href: url,
                        download: params.filename
                    });
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                    alerts({
                        title: `Se descargó un archivo XLS`,
                        icon: `success`
                    });
                } else {
                    alerts({
                        title: `Error al descargar el archivo XLS`,
                        icon: `error`
                    });
                }
            }
            http.responseType = `arraybuffer`;
            http.send(data);
        } else {
            alerts({
                title: `Etiqueta no valida para excel`,
                icon: `info`
            });
        }
    }
}
// function xlsx(array) { }