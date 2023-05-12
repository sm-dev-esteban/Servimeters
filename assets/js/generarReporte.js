$(document).ready(function (){
    console.log('Cargando Reporte...');

    $('#generarExcel').click(function (e){
        e.preventDefault();
        console.log('Generar Excel');

        let fechaInicio = $('#fechaInicio').val();
        let fechaFin = $('#fechaFin').val();

        console.log('Fechas ', fechaInicio, fechaFin);

    })
})