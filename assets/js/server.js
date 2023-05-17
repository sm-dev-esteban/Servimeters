const server = new WebSocket('ws://localhost:8080');

server.onopen = function (e) {
    console.log("Socket active");
}

server.onmessage = function (e) {
    resp = JSON.parse(e.data);

    if (resp.type == "alert") {
        arrayAlert = resp.data.arrayAlert ? resp.data.arrayAlert : {};
        typeAlert = resp.data.typeAlert ? resp.data.typeAlert : "Sweetalert2";
        alerts(arrayAlert, typeAlert);
    }

};