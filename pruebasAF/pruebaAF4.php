<?php
    define("IDENT", 20230504212636);
    if (isset($_GET["action"]) && $_GET["action"] == "UPDATE") {
        // $pruebaAF4 = new AutomaticForm(["data" => $_POST["data"], "file" => $_FILES["file"]], "prueba4", "UPDATE", #); ejemplos
        // $pruebaAF4 = new AutomaticForm(["data" => $_POST["data"], "file" => $_FILES["file"]], "prueba4", "UPDATE", ["@primary" => #]); ejemplos
        // $pruebaAF4 = new AutomaticForm(["data" => $_POST["data"], "file" => $_FILES["file"]], "prueba4", "UPDATE", ["id" => #]); ejemplos
        $pruebaAF4 = new AutomaticForm(["data" => $_POST["data"], "file" => $_FILES["file"]], "prueba4", "UPDATE", ["ident" => IDENT]);
        echo json_encode($pruebaAF4->execute(), JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        $pruebaAF4 = new AutomaticForm(["data" => ["ident" => IDENT]], "prueba4", "INSERT");
        $pruebaAF4->execute();
    }
?>

<form>
    <div>
        <label for="">test - test</label>
        <input type="text" name="data[test1]" id="test1">
    </div>
    <div>
        <label for="">test - number</label>
        <input type="number" name="data[test2]" id="test2">
    </div>
    <div>
        <label for="">test - date</label>
        <input type="date" name="data[test3]" id="test3">
    </div>
    <div>
        <label for="">test - file</label>
        <input type="file" accept="image/*" name="file[test4]" id="test4">
    </div>
    <div>
        <button type="submit">Enviar</button>
    </div>
</form>

<script src="../AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<script src="../AdminLTE-3.2.0/plugins/sweetalert2/sweetalert2.all.js"></script>
<script src="../assets/js/master.js"></script>

<script>
    $(document).ready(function () {
        $(`form`).on("submit", function (e) {
            e.preventDefault(); // prevengo el evento
            $.ajax(`?action=UPDATE`, {
                type: "POST",
                dataType: "JSON",
                data: new FormData(this),
                processData: false,
                cache: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                }
            })
        })
    });
</script>