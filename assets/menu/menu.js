$(document).ready(() => {
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/assets/menu/menu.php`

    const handleToggleDark = () => {
        const $navbar = $(`nav.navbar`)
        const $sidebar = $(`aside.main-sidebar`)
        const $body = $(`body`)

        // navbar
        if ($navbar.hasClass("navbar-light"))
            $navbar.removeClass("bg-white navbar-light").addClass("bg-dark navbar-dark")
        else
            $navbar.removeClass("bg-dark navbar-dark").addClass("bg-white navbar-light")

        // sidebar
        if ($sidebar.attr("class").includes("-light-"))
            $sidebar.attr("class", $sidebar.attr("class").replace("-light-", "-dark-"))
        else
            $sidebar.attr("class", $sidebar.attr("class").replace("-dark-", "-light-"))

        // body
        $body.toggleClass("dark-mode")

    }, handleDisconnect = async () => {
        const request = await fetch(`${URL_BACKEND}?action=disconnect`)

        if (request.status === 200) window.location.href = Config.BASE_SERVER
    }, handleInternalRoute = function (e) {
        e.preventDefault()

        const $this = $(this)
        const title = $this.text()
        const url = $this.attr("href")

        if ($this.hasClass("nav-link")) {
            const $active = $(`.nav-link.active`)
            $active.removeClass("active")
            $this.addClass("active")
        }

        route(title, url, true)
    }

    $(`[data-widget="toggle-dark"]`).on("click", handleToggleDark)
    $(document).on(`click`, `#btnDisconnect`, handleDisconnect)
    $(document).on(`click`, `[href!="#"][href*="${Config.BASE_SERVER}"]:not([target="_blank"])`, handleInternalRoute)

    window.addEventListener('popstate', async function (e) {
        const currentState = e.state
        if (currentState) await route(currentState.title, currentState.url)
    })
})

function codeError() {

    const $error = $(`textarea[data-error]`)

    if ($error.length) {
        const line = parseInt($error.data('line')) - 1
        const message = $error.data('message')
        const value = $error.val()
        const type = $error.data('error')

        const editor = CodeMirror.fromTextArea($error.get(0), {
            theme: "ayu-dark",
            value: value,
            matchBrackets: true,
            indentUnit: 4,
            indentWithTabs: true,
            lineNumbers: true
        })

        editor.addLineClass(line, "text-decoration", `CodeMirror-line-${type}`)

        $error.siblings().popover({
            trigger: 'hover',
            content: message
        })

    }
}

const route = async (title, url = "index", usePreloader = false) => {
    const Config = CONFIG()
    const URL_BACKEND = `${Config.BASE_SERVER}/assets/menu/menu.php`

    const request = await fetch(`${URL_BACKEND}?action=checkSession`)
    const checkSession = await request.json()

    if (checkSession.status === true) {
        url = url.replace(Config.BASE_SERVER, "").replace(/^\/+|\/+$/g, '').trim() || "index"

        if (url && !["#", ""].includes(url)) {
            const $preloader = $(`.preloader`)
            const headTitle = $(`head title`)

            url = `/${url}`

            history.pushState({
                title: title,
                url: url
            }, title, `${Config.BASE_SERVER}${url}`)

            headTitle.html(title)

            $.ajax(`${URL_BACKEND}?action=view`, {
                type: "POST",
                dataType: "HTML",
                data: { view: url },
                beforeSend: () => {
                    if (usePreloader) $preloader.removeAttr(`style`).find(`img`).removeAttr(`style`)
                },
                success: (response) => $(`[data-router]`).replaceWith(response),
                complete: () => {
                    codeError()

                    const loadJS = $(`LOAD-SCRIPT`)
                    if (loadJS.length) {
                        JSON.parse(loadJS.text()).forEach((e) => $.getScript(e))
                        loadJS.remove()
                    }

                    if (usePreloader) setTimeout(() => $preloader.css({ height: 0 }).find(`img`).css({ display: "none" }), 1000)
                }
            })
        }
    } else window.location.href = Config.BASE_SERVER
}