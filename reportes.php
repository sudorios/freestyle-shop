<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once './conexion/cone.php';

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Consulta para obtener el conteo de productos por estado
$sql = "SELECT estado, COUNT(*) as cantidad FROM inventario_sucursal GROUP BY estado ORDER BY estado";
$result = pg_query($conn, $sql);

$estados = [];
$cantidades = [];
while ($row = pg_fetch_assoc($result)) {
    $estados[] = strtoupper($row['estado']);
    $cantidades[] = (int)$row['cantidad'];
}

// Consulta para pedidos por mes
$sql_pedidos_mes = "SELECT TO_CHAR(fecha, 'YYYY-MM') AS mes, COUNT(*) AS cantidad FROM pedido GROUP BY mes ORDER BY mes";
$res_pedidos_mes = pg_query($conn, $sql_pedidos_mes);
$meses = [];
$cant_pedidos = [];
while ($row = pg_fetch_assoc($res_pedidos_mes)) {
    $meses[] = $row['mes'];
    $cant_pedidos[] = (int)$row['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Inventario</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include_once './includes/head.php'; ?>
</head>
<body id="main-content" class="ml-72 mt-20">
    <?php include_once './includes/header.php'; ?>
    <main>
        <div class="container mx-auto px-4 mt-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Reporte de Inventario por Estado</h2>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6 mb-10">
                <canvas id="graficoInventarioEstado" width="400" height="150"></canvas>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4">Pedidos por Mes</h3>
                <canvas id="graficoPedidosMes" width="400" height="150"></canvas>
            </div>
        </div>
    </main>
    <script>
        const ctx = document.getElementById('graficoInventarioEstado').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($estados); ?>,
                datasets: [{
                    label: 'Cantidad de productos',
                    data: <?php echo json_encode($cantidades); ?>,
                    backgroundColor: [
                        'rgba(34,197,94,0.7)', // verde
                        'rgba(234,179,8,0.7)', // amarillo
                        'rgba(239,68,68,0.7)'  // rojo
                    ],
                    borderColor: [
                        'rgba(34,197,94,1)',
                        'rgba(234,179,8,1)',
                        'rgba(239,68,68,1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Productos por Estado (CUADRA, FALTA, SOBRA)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Cantidad' }
                    },
                    x: {
                        title: { display: true, text: 'Estado' }
                    }
                }
            }
        });

        const ctxPedidosMes = document.getElementById('graficoPedidosMes').getContext('2d');
        const chartPedidosMes = new Chart(ctxPedidosMes, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($meses); ?>,
                datasets: [{
                    label: 'Cantidad de pedidos',
                    data: <?php echo json_encode($cant_pedidos); ?>,
                    backgroundColor: 'rgba(59,130,246,0.7)',
                    borderColor: 'rgba(59,130,246,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Pedidos por Mes'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Cantidad' }
                    },
                    x: {
                        title: { display: true, text: 'Mes' }
                    }
                }
            }
        });
    </script>
    <?php include_once './includes/footer.php'; ?>
</body>
</html> 