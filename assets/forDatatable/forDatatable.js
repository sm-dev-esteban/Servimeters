const DATATABLE_LANGUAGE = {
    language: {
        sProcessing: "Procesando...",
        sLengthMenu: "Mostrar _MENU_ registros",
        sZeroRecords: "No se encontraron resultados",
        sEmptyTable: "Ningún dato disponible en esta tabla",
        sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        sInfoEmpty: "Sin registros para mostrar. ¯\\_(ツ)_/¯",
        sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
        sSearch: "Buscar:",
        sInfoThousands: ",",
        sLoadingRecords: "Cargando...",
        oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior"
        },
        oAria: {
            sSortAscending: ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente"
        },
        select: {
            rows: {
                0: '',
                1: 'Seleccionado 1 fila',
                _: 'Seleccionado %d filas',
            }
        }
    }
}, DATATABLE_BUTTONS = {
    // dom: 'Bfrtip',
    buttons: [
        "copy",
        "csv",
        "excel",
        "pdf",
        "print",
        "colvis"
    ]
}, DATATABLE_ALL = $.extend(DATATABLE_LANGUAGE, DATATABLE_BUTTONS)

const SELECT2_UTILS = {
    ajax: {
        url: null,
        dataType: "json",
        processResults: (data) => data
    },
    placeholder: "Seleccione..."
}