<?php
    if (isset($_POST) && !empty(count($_POST))) {
        // ejemplo 1
        // puedo enviar los datos especificando los datos del arreglo que son (data - file) 
        $pruebaA2 = new AutomaticForm(["data" => $_POST, "file" => $_FILES], "prueba2"); // opcion 1

        print "<h1>Execute</h1>";
        print "<pre>";
        print_r($pruebaA2->execute());
        print "</pre>";

        print "<h1>Show allData</h1>";
        print "<pre>";
        print_r($pruebaA2->getalldata());
        print "</pre>";
    }
?>
<form action="#" method="POST" enctype="multipart/form-data">
    <div>
        <label for="test1"> test - text</label>
        <input type="text" name="test1" id="test1">
    </div>
    <div>
        <label for="test2"> test - number</label>
        <input type="number" name="test2" id="test2">
    </div>
    <div>
        <label for="test3"> test - date</label>
        <input type="date" name="test3" id="test3">
    </div>
    <div>
        <label for="test4"> test - file</label>
        <input type="file" accept="image/*" name="test4" id="test4">
    </div>
    <div>
        <button type="submit">Enviar</button>
    </div>
</form>