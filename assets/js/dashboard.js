window.renderDashboardCharts = function({
    estados, cantidades, meses, cant_pedidos, meses_ing, cant_ingresos, top_productos, top_cantidades
}) {
    new Chart(document.getElementById('graficoInventarioEstado'), {
        type: 'doughnut',
        data: {
            labels: estados,
            datasets: [{
                data: cantidades,
                backgroundColor: ['#22c55e','#eab308','#ef4444','#3b82f6','#a855f7'],
            }]
        }
    });
    new Chart(document.getElementById('graficoPedidosMes'), {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Pedidos',
                data: cant_pedidos,
                backgroundColor: '#3b82f6'
            }]
        }
    });
    new Chart(document.getElementById('graficoIngresosMes'), {
        type: 'line',
        data: {
            labels: meses_ing,
            datasets: [{
                label: 'Ingresos',
                data: cant_ingresos,
                backgroundColor: '#f59e42',
                borderColor: '#f59e42',
                fill: false
            }]
        }
    });
    new Chart(document.getElementById('graficoTopProductos'), {
        type: 'bar',
        data: {
            labels: top_productos,
            datasets: [{
                label: 'Vendidos',
                data: top_cantidades,
                backgroundColor: '#a855f7'
            }]
        },
        options: {
            indexAxis: 'y'
        }
    });
} 