<?php
define("IDENT", 20230504212636);
if (isset($_GET["action"]) && $_GET["action"] == "INSERT") {
    $pruebaAF5 = new AutomaticForm($_FILES, "prueba5");
    echo json_encode($pruebaAF5->execute(true, true), JSON_UNESCAPED_UNICODE);
    exit;
}
?>

<form>
    <div>
        <label for="">regular file</label>
        <input type="file" accept="image/*" name="file[test1]" id="test1">
    </div>
    <div>
        <label for="">multiple file</label>
        <input type="file" accept="image/*" multiple name="file[test2][]" id="test2">
    </div>
    <div>
        <button type="submit">Enviar</button>
    </div>
</form>

<script src="../AdminLTE/plugins/jquery/jquery.min.js"></script>
<script src="../AdminLTE/plugins/sweetalert2/sweetalert2.all.js"></script>
<script src="../assets/js/master.js"></script>

<script>
    $(document).ready(function() {
        $(`form`).on("submit", function(e) {
            e.preventDefault(); // prevengo el evento
            $.ajax(`?action=INSERT`, {
                type: "POST",
                dataType: "JSON",
                data: new FormData(this),
                processData: false,
                cache: false,
                contentType: false,
                success: function(response) {
                    console.log(response);
                }
            })
        })
    });
</script>