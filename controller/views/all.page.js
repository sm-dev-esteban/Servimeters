// En esta parte va codigo de cosas que puedan inicializar en varias paginas a la vez
// Nota: cree este archivo como ayuda para algunas cosas que se deben inicializar o cosas asi por el estilo

//------------------------------------------------------------------------------------------------------------------------------------------------------
// DataTable
// Nota: Si quieren usar valores diferentes no usar tableD mejor usar un extend para unir y reemplazar con sus nuevos valores
// ejemplo: $("ejemplo").DataTable($.extend(datatableParams, { "nuevos": "parametros" }))
//------------------------------------------------------------------------------------------------------------------------------------------------------
$("table.tableD").each(function() {
    $(this).DataTable(datatableParams).buttons().container().appendTo('.dataTables_wrapper .col-md-6:eq(0)');
});
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Placeholder
//------------------------------------------------------------------------------------------------------------------------------------------------------
$("select.select2").each(function() {
    $(this).select2({
        language: language
    });
});
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Placeholder
//------------------------------------------------------------------------------------------------------------------------------------------------------
$(`label[for!=""]`).each(function() {
    let text = $(this).text();
    let iden = $(this).attr("for");
    if (!$(`#${iden}`).attr(`placeholder`)) { // valida cualquier respuesta regativa
        $(`#${iden}`).attr(`placeholder`, text);
    }
});
//------------------------------------------------------------------------------------------------------------------------------------------------------
// Maxlength
//------------------------------------------------------------------------------------------------------------------------------------------------------
$(`[maxlength]`).on(`input`, function () {
    let ml = Number($(this).attr("maxlength"));
    let value = $(this).val();
    if (value.length > ml) { // valida si ya exedio el numero de caracteres
        alerts({title: `maximo de ${ml} caracteres`, icon: `info`, position: `center`});
        $(this).val(value.slice(0, ml));
    }
});