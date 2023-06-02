// No sé qué tanto se pueda agrandar un archivo como este, así que mejor lo manejo comprimido.
// https://www.toptal.com/developers/javascript-minifier
try {
    server = new WebSocket('ws://localhost:8080/');
}
catch (err) { }

server.onerror = function (error) {
    alerts({ icon: "error", title: "Servidor inactivo.", position: "bottom-start" });
    resp = $.ajax(`${location.origin}/${location.pathname.split("/")[1]}/bin/start.php`, { async: false });
    // if (resp.status == 200) {
    //     setTimeout(() => {
    //         server = new WebSocket('ws://localhost:8080/');
    //     }, 1000);
    // }
};

server.onopen = function (e) {
    session = automaticForm("readSession");

    localStorage.setItem("rol", session.rol ? session.rol : "empleado");
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