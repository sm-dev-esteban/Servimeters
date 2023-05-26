// No sé qué tanto se pueda agrandar un archivo como este, así que mejor lo manejo comprimido.
// https://www.toptal.com/developers/javascript-minifier

const server = new WebSocket('ws://localhost:8080');

server.onopen = function (e) {
    session = automaticForm("readSession");

    localStorage.setItem("rol", session.rol);
    localStorage.setItem("usuario", session.usuario);
    localStorage.setItem("email", session.email);

}

server.onmessage = function (e) {
    resp = JSON.parse(e.data);
    // Enviar resp.type con la acción que quieran realizar y diviertanse :).
    if (resp.type == "alerts") {
        arrayAlert = resp.data.arrayAlert ? resp.data.arrayAlert : {};
        typeAlert = resp.data.typeAlert ? resp.data.typeAlert : "Sweetalert2";

        rol = String(localStorage.getItem("rol"));
        usuario = String(localStorage.getItem("usuario"));

        if (resp.rol == rol) {
            alerts(arrayAlert, typeAlert);
        } else if (resp.usuario == usuario) {
            alerts(arrayAlert, typeAlert);
        } else if (!resp.rol && !resp.usuario) {
            alerts(arrayAlert, typeAlert);
        }
    }

}