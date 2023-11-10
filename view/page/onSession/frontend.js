/*-- 2023-09-01 08:40:49 --*/

$(document).ready(async () => {
    // clear Storage
    localStorage.clear()
    sessionStorage.clear()

    location.replace(GETCONFIG("SERVER_SIDE"))
})