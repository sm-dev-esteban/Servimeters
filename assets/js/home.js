// class chartMaster {
//     constructor() {
//         this.charts = {}
//     }
//     show () {}
//     modify () {}
// }
charts = {};

$(`canvas[data-chart-ident]`).each(function () {
    let $canvas, $chart, $type;
    $canvas = $(this);
    $chart = $canvas.data("chart-ident");
    $type = $canvas.data("chart-type") ?? "line";
    $.ajax(`../controller/chart.controller.php?chart=${$chart}`, {
        dataType: "JSON",
        success: function (r) {
            if (r.knob.length > 0) {
                for (i in r.knob) {
                    // datos
                    let knob_count, knob_data, knob_title;
                    knob_count = r.knob.length;
                    knob_data = r.knob[i]["data"] ?? false;
                    knob_title = r.knob[i]["title"] ?? false;
                    // datos

                    // campos
                    let $div_knob, $input_knob, $div_t_knob;
                    $div_knob = $(`[data-chart-knob="${$chart}"]`);
                    $input_knob = $($div_knob.find(`[data-knob="${$chart}"]`).get(i));
                    $div_t_knob = $($input_knob.find("[data-chart-knob-title]").get(i));
                    // campos

                    if ($input_knob.length == 1) {
                        $input_knob.val(knob_data ?? 0).trigger(`change`);
                        $div_t_knob.html(knob_title ?? $div_t_knob.html());
                    } else {
                        $div_knob.append(`
                        <div class="col-5 col-xl-${knob_count > 12 ? 1 : String(12 / knob_count).split(".")[0]} text-center">
                            <input type="text" class="knob" data-knob="${$chart}" value="${knob_data}" data-readonly="true" data-width="60" data-height="60" data-fgColor="#28a745">
                            <div data-chart-knob-title>${knob_title ?? ""}</div>
                        </div>
                        `);
                        $input_knob = $div_knob.find(`[data-knob="${$chart}"]`);
                        $input_knob.knob();
                    }
                }
            }
            config = {
                type: $type,
                data: {
                    labels: r.labels,
                    datasets: [
                        {
                            label: r.label,
                            data: r.data,
                            fill: false,
                            borderWidth: 2,
                            lineTension: 0,
                            spanGaps: true,
                            borderColor: `#0e71b1`,
                            pointRadius: 3,
                            pointHoverRadius: 7,
                            pointColor: `#0e71b1`,
                            pointBackgroundColor: `#0e71b1`
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontColor: `#0e71b1`
                            },
                            gridLines: {
                                display: false,
                                color: `#0e71b1`,
                                drawBorder: false
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                stepSize: 5000,
                                fontColor: `#0e71b1`
                            },
                            gridLines: {
                                display: true,
                                color: `#0e71b1`,
                                drawBorder: false
                            }
                        }]
                    }
                }
            };
            charts[$chart] = new Chart($canvas, config);
        }
    });
});

function modifyChart(chart, data, modify) {
    switch (modify) {
        case "type":
            charts[chart].config.type = data;
            break;
        case "labels":
            charts[chart].data.labels = data;
            break;
    }
    charts[chart].update();
}