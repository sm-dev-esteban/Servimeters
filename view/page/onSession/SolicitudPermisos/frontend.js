/*-- 2023-11-22 10:29:13 --*/

$(document).ready(async () => {
    $('#reservation').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY hh:mm A'
        }
    })
})