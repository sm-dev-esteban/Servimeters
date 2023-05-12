<?php
    if (isset($_POST) && !empty(count($_POST))) {
        // ejemplo 1
        // puedo enviar los datos especificando los datos del arreglo que son (data - file) 
        $pruebaAF3 = new AutomaticForm(["data" => $_POST["data"], "file" => $_FILES["file"]], "prueba3"); // opcion 1
        // ejemplo 2
        // como el formulario ya esta los name como un arreglo puedo unirlo y enviarlo (name="data[#]" - name="file[#]") 
        $pruebaAF3 = new AutomaticForm(array_merge($_POST, $_FILES), "prueba3", "UPDATE"); // opcion 2

        print "<h1>Execute</h1>";
        print "<pre>";
        print_r($pruebaAF3->execute());
        print "</pre>";

        print "<h1>Show allData</h1>";
        print "<pre>";
        print_r($pruebaAF3->getalldata());
        print "</pre>";

        print "<h1>Show file</h1>";
        print "<pre>";
        print_r($pruebaAF3->getconfig());
        print "</pre>";
    }
?>
<form action="#" method="POST" enctype="multipart/form-data">
    <div>
        <label for="test1"> test - text</label>
        <input type="text" name="data[test1]" id="test1">
    </div>
    <div>
        <label for="test2"> test - number</label>
        <input type="number" name="data[test2]" id="test2">
    </div>
    <div>
        <label for="test3"> test - date</label>
        <input type="date" name="data[test3]" id="test3">
    </div>
    <div>
        <label for="test4"> test - file</label>
        <input type="file" accept="image/*" name="file[test4]" id="test4">
    </div>
    <div>
        <button type="submit">Enviar</button>
    </div>
</form>