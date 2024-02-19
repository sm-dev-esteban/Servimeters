class ChartJS {
    constructor(canvas) {
        this.uid = Date.now()
        this.canvas = document.querySelector(canvas)

        this.settings = {
            type: "bar",
            data: {
                labels: [],
                datasets: [{
                    label: "values",
                    data: [],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                tooltips: {
                    mode: "index",
                    intersect: false
                }, hover: {
                    mode: "index",
                    intersect: false
                }
            }
        }


        this.myChart = null

        if (!this.canvas) throw Error(`Selector no valido: ${canvas}`)
    }

    getSettings() {
        return this.settings
    }

    setSettings(newSettings) {
        if (typeof newSettings !== "object") {
            this.settings = { ...this.settings, ...newSettings }
            this.updateChart()
        }
    }

    setLabels(labels) {
        if (Array.isArray(labels)) this.settings.data.labels = labels
    }

    setDatasets(datasets) {
        if (typeof datasets === "object") this.settings.data.datasets = datasets
    }

    setOptions(options) {
        // if (typeof newSettings !== "object") this.settings.options = { ...this.settings.options, ...options }
        if (typeof newSettings !== "object") this.settings.options = options
    }

    setColors(colors, borders) {
        if (Array.isArray(colors) && Array.isArray(borders)) {
            this.settings.data.datasets.forEach((dataset) => {
                if (colors.length) dataset.backgroundColor = colors
                if (borders.length) dataset.borderColor = borders
            })
            this.updateChart()
        }
    }

    setAnimationOptions(animationOptions) {
        if (typeof animationOptions === "object") {
            this.settings.options.animation = animationOptions
            this.updateChart()
        }
    }

    setLegendOptions(legendOptions) {
        if (typeof legendOptions === "object") {
            this.settings.options.legend = { ...this.settings.options.legend, ...legendOptions }
            this.updateChart()
        }
    }

    setAxisOptions(axisOptions) {
        if (typeof axisOptions === "object") {
            this.settings.options.scales = { ...this.settings.options.scales, ...axisOptions }
            this.updateChart()
        }
    }

    setTooltipOptions(tooltipOptions) {
        if (typeof tooltipOptions === "object") {
            this.settings.options.tooltips = { ...this.settings.options.tooltips, ...tooltipOptions }
            this.updateChart()
        }
    }

    updateOptions(newOptions) {
        if (typeof newOptions === "object") {
            this.settings = { ...this.settings, ...newOptions }
            this.updateChart()
        }
    }

    addCustomEvent(eventName, callback) {
        if (typeof eventName === "string" && typeof callback === "function") this.settings.options[eventName] = callback
    }

    exportChart(format = 'png') {
        const canvas = document.getElementById(`myChart${this.uid}`)
        if (canvas) {
            const image = canvas.toDataURL(`image/${format}`)
            const link = document.createElement('a')
            link.href = image
            link.download = `myChart.${format}`
            link.click()
        }
    }

    validateType(find) {
        const valid = [
            "bar",
            "line",
            "pie",
            "doughnut",
            "radar"
        ]

        const res = valid.filter((type) => type == find.trim().toLowerCase())

        return res.length && res.length > 0
    }

    createChart(type = "bar") {
        if (this.myChart) this.destroyChart()

        this.settings.type = this.validateType(type) ? type : "bar"

        const ctx = this.canvas.getContext("2d")

        this.myChart = new Chart(ctx, this.settings)

        return this.myChart
    }

    updateChart() {
        if (this.myChart) this.myChart.update()
    }

    destroyChart() {
        if (this.myChart) this.myChart.destroy()
    }

    addData(label, data) {
        if (label && data) {
            this.settings.data.labels.push(label)
            this.settings.data.datasets.forEach((dataset) => dataset.data.push(data))
            this.updateChart()
        }
    }

    updateChartType(type) {
        this.destroyChart()
        this.myChart = this.createChart(type)
    }

    removeData(datasetIndex, index) {
        if (this.settings.data.labels.length > index) {
            this.settings.data.labels.splice(index, 1)
            this.settings.data.datasets[datasetIndex].data.splice(index, 1)
            this.updateChart()
        }
    }
}

// export default ChartJS