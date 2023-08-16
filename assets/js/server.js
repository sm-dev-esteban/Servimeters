// No sé qué tanto se pueda agrandar un archivo como este, así que mejor lo manejo comprimido.
// https://www.toptal.com/developers/javascript-minifier
$(document).ready(async function () {
    config = await loadConfig();
    try {
        server = new WebSocket(`ws://${location.hostname}:${config.WEBSOCKET}/`);

    }
    catch (err) { }

    server.onerror = function (e) {
        alerts({ icon: "error", title: "WebSocket inactivo.", position: "bottom-start" });
        resp = $.ajax(`${location.origin}/${location.pathname.split("/")[1]}/bin/start.php`, { async: false });
    };

    server.onopen = function (e) {
        session = automaticForm("readSession");

        localStorage.setItem("rol", session.rol ?? "NA");
        localStorage.setItem("gestiona", session.gestiona ?? "NA");
        localStorage.setItem("usuario", session.usuario ?? "NA");
        localStorage.setItem("email", session.email ?? "NA");

    }

    server.onmessage = function (e) {
        resp = JSON.parse(e.data);
        // Enviar resp.type con la acción que quieran realizar y diviertanse :)
        if ((resp.type ? true : false)) {
            if (resp.type == "alerts") {
                arrayAlert = resp.data.arrayAlert ? resp.data.arrayAlert : {};
                typeAlert = resp.data.typeAlert ? resp.data.typeAlert : "Sweetalert2";

                rol = String(automaticForm("getSession", ["rol"]));
                usuario = String(automaticForm("getSession", ["usuario"]));

                if (resp.rol == rol) {
                    alerts(arrayAlert, typeAlert);
                } else if (resp.usuario == usuario) {
                    alerts(arrayAlert, typeAlert);
                } else if (!resp.rol && !resp.usuario) {
                    alerts(arrayAlert, typeAlert);
                }
            }
        } else {
            console.log(resp);
        }
    }

    sendWS = (str) => {
        if (server.readyState == 1) {
            server.send(
                JSON.stringify(
                    str
                )
            )
        } else {
            alerts({ icon: "error", title: "WebSocket inactivo.", position: "bottom-start" });
        }
    }

})