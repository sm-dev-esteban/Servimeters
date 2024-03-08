$(window).on("focus", () => sessionStorage.setItem("windowIsFocus", true))
$(window).on("blur", () => sessionStorage.setItem("windowIsFocus", false))

const updateDatatable = (el = "table.dataTable") => $(el).DataTable().ajax.reload(null, false)

const swalFire = async (main = {}, remove = []) => {
    main = $.extend({
        title: `Cuerpo`,
        input: `textarea`,
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        focusConfirm: false
    }, main)

    if (remove.length) remove.forEach(r => delete main[r])

    const { value: response } = await Swal.fire(main)

    return response
}