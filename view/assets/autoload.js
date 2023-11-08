
const LOADSCRIPT = () => {
    $scripts = $(`LOAD-SCRIPT`);
    JSON.parse($scripts.text()).forEach(e => {
        $.getScript(e)
    });
    $scripts.remove();
}, LOADCSS = () => {
    // $css = $(`LOAD-CSS`);
    // JSON.parse($css.text()).forEach(e => {
    //     $.get(e, (data) => {
    //         $(`<style>`).html(data).appendTo("[data-router]")
    //     });
    // });
    // $css.remove();
}, LOADALL = () => {
    // LOADCSS();
    LOADSCRIPT();
}
