let tipo_suscripcion=[];
let cant_ventas_suscripcion=[];
let products=[];
let cant_products=[];

function cargarDatoSuscripcion(tipo, cant){
    tipo_suscripcion.push(tipo);
    cant_ventas_suscripcion.push(cant);
}

function cargarDatoProducto(product, cant){
    products.push(product);
    cant_products.push(cant);
}

function iniciar_graficos(){
    'use strict';
    var dataS = {
        labels: tipo_suscripcion,
        datasets: [{
            label: 'Cantidad de compras',
            data: cant_ventas_suscripcion,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 2,
            fill: false
        }]
    };




    var options = {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                },
                gridLines: {
                    color: "rgba(204, 204, 204,0.1)"
                }
            }],
            xAxes: [{
                gridLines: {
                    color: "rgba(204, 204, 204,0.1)"
                }
            }]
        },
        legend: {
            display: true
        },
        elements: {
            point: {
                radius: 0
            }
        },
        animation: {
            animateScale: true,
            animateRotate: true
        }
    };


    var tortaData = {
        datasets: [{
            data: cant_products,
            backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)'
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
        }],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: products
    };
    var tortaOptions = {
        responsive: true,
        animation: {
            animateScale: true,
            animateRotate: true
        }
    };



    if ($("#suscripcionChart").length) {
        var barChartCanvas = $("#suscripcionChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: dataS,
            options: options
        });
    }



    if ($("#productChart").length) {
        var tortaChartCanvas = $("#productChart").get(0).getContext("2d");
        var doughnutChart = new Chart(tortaChartCanvas, {
            type: 'doughnut',
            data: tortaData,
            options: tortaOptions
        });
    }


}