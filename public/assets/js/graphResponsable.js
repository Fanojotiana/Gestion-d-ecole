document.addEventListener("DOMContentLoaded", function () {
    var options = {
        chart: {
            type: "area",
            height: 350,
            toolbar: { show: false },
        },
        series: [
            {
                name: "Paiements",
                data: [
                    300000, 400000, 350000, 500000, 300000, 600000, 700000, 0,
                    0, 0, 0, 0,
                ], // Ex. Jan à Déc
            },
        ],
        xaxis: {
            categories: [
                "Jan",
                "Fév",
                "Mar",
                "Avr",
                "Mai",
                "Juin",
                "Juil",
                "Août",
                "Sept",
                "Oct",
                "Nov",
                "Déc",
            ],
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            curve: "smooth",
        },
        colors: ["#3f51b5"],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.6,
                opacityTo: 0.1,
                stops: [0, 90, 100],
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toLocaleString("fr-FR") + " Ar";
                },
            },
        },
    };

    var chart = new ApexCharts(
        document.querySelector("#paiements-chart"),
        options
    );
    chart.render();
});
