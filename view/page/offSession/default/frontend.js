$(document).ready(() => {
    $(`#signIn`).on("submit", function (e) {
        e.preventDefault();
        const $this = $(this); // form
        const $submit = $this.find(":submit").eq(0); // btn submit
        const $submit_html = $submit.html(); // text btn submit

        $.ajax(`${GETSERVERSIDE()}/View/page/offSession/default/backend.php?action=login`, {
            type: "POST",
            dataType: "JSON",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: () => {
                $submit.html(`
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                `).css({
                    "font-size": "1rem"
                });
            },
            success: (response) => {
                if (response.status === true) location.replace("./")
                else console.log("check your credendials");
            },
            complete: () => {
                setTimeout(() => {
                    $submit.html($submit_html);
                }, 600);
            }
        })
    });
})