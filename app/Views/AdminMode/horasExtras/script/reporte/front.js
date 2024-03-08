$(document).ready(async () => {
    $(`#descargarPRN`).click(() => {
        var prnData = ``

        $(`#table-result tbody tr`).each((_, tr) => {
            $(tr).find(`td`).each((__, td) => prnData += $(td).text() + `\t`)
            prnData += `\n`
        })

        const $a = $(`<a></a>`)
        $a.attr(`href`, `data:application/prn;charset=utf-8,${encodeURIComponent(prnData)}`)
        $a.attr(`download`, `tabla.prn`)
        $a.css({ "display": `none` })
        $(`body`).append($a)
        $a[0].click()
        $a.remove()
    });
})