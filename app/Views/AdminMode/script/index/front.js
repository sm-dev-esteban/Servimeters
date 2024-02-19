$(document).ready(async () => {
    const chartJS = new ChartJS("#line-chart")

    chartJS.setLabels(data.labels || null)
    chartJS.setDatasets(data.datasets || null)

    chartJS.createChart("line")
})
