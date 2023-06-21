(() => {
    $(`canvas[data-chart]`).each(function () {
        $canvas = $(this);
        $chart = $canvas.data("chart");
        $.ajax(`../controller/chart.controller.php?chart=${$chart}`, {
            dataType: "JSON",
            success: function (r) {
                if (r.knob.length > 0) {
                    for (data in r.knob) {
                        $input_knob = $($(`[data-knob="${$chart}"]`).get(data));
                        $input_knob.val(r.knob[data]).trigger(`change`);
                    }
                }
                new Chart($canvas, {
                    type: `line`,
                    data: {
                        labels: r.labels,
                        datasets: [
                            {
                                label: r.label,
                                fill: false,
                                borderWidth: 2,
                                lineTension: 0,
                                spanGaps: true,
                                borderColor: `#0e71b1`,
                                pointRadius: 3,
                                pointHoverRadius: 7,
                                pointColor: `#0e71b1`,
                                pointBackgroundColor: `#0e71b1`,
                                data: r.data
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
                });
            }
        });
    });
})()    
