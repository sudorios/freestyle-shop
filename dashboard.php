<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'utils/functions.php';
requireLogin();
include_once 'conexion/cone.php';
include_once 'includes/head.php';
include_once 'includes/header.php';
include_once 'utils/queries.php';

$total_productos = pg_fetch_result(pg_query($conn, getTotalProductosQuery()), 0, 0);
$total_pedidos = pg_fetch_result(pg_query($conn, getTotalPedidosQuery()), 0, 0);
$total_ingresos = pg_fetch_result(pg_query($conn, getTotalIngresosQuery()), 0, 0);
$total_usuarios = pg_fetch_result(pg_query($conn, getTotalUsuariosQuery()), 0, 0);

$res_estados = pg_query($conn, getProductosPorEstadoQuery());
$estados = [];
$cantidades = [];
while ($row = pg_fetch_assoc($res_estados)) {
    $estados[] = strtoupper($row['estado']);
    $cantidades[] = (int)$row['cantidad'];
}

$res_pedidos_mes = pg_query($conn, getPedidosPorMesQuery());
$meses = [];
$cant_pedidos = [];
while ($row = pg_fetch_assoc($res_pedidos_mes)) {
    $meses[] = $row['mes'];
    $cant_pedidos[] = (int)$row['cantidad'];
}

$res_ingresos_mes = pg_query($conn, getIngresosPorMesQuery());
$meses_ing = [];
$cant_ingresos = [];
while ($row = pg_fetch_assoc($res_ingresos_mes)) {
    $meses_ing[] = $row['mes'];
    $cant_ingresos[] = (int)$row['cantidad'];
}

$res_top = @pg_query($conn, getTopProductosVendidosQuery(5));
$top_productos = [];
$top_cantidades = [];
if ($res_top) {
    while ($row = pg_fetch_assoc($res_top)) {
        $top_productos[] = $row['nombre_producto'];
        $top_cantidades[] = (int)$row['total_vendidos'];
    }
}
?>
<body id="main-content" class="ml-72 mt-20">
<main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <?php
                renderCard('blue', $total_productos, 'Productos');
                renderCard('green', $total_pedidos, 'Pedidos');
                renderCard('yellow', $total_ingresos, 'Ingresos');
                renderCard('purple', $total_usuarios, 'Usuarios');
            ?>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold mb-4">Productos por Estado</h3>
                <canvas id="graficoInventarioEstado"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold mb-4">Pedidos por Mes</h3>
                <canvas id="graficoPedidosMes"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold mb-4">Ingresos por Mes</h3>
                <canvas id="graficoIngresosMes"></canvas>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="font-bold mb-4">Top 5 Productos Más Vendidos</h3>
                <canvas id="graficoTopProductos"></canvas>
            </div>
        </div>
    </main>
    <script src="assets/js/dashboard.js"></script>
    <script>
        window.renderDashboardCharts({
            estados: <?php echo json_encode($estados); ?>,
            cantidades: <?php echo json_encode($cantidades); ?>,
            meses: <?php echo json_encode($meses); ?>,
            cant_pedidos: <?php echo json_encode($cant_pedidos); ?>,
            meses_ing: <?php echo json_encode($meses_ing); ?>,
            cant_ingresos: <?php echo json_encode($cant_ingresos); ?>,
            top_productos: <?php echo json_encode($top_productos); ?>,
            top_cantidades: <?php echo json_encode($top_cantidades); ?>
        });
    </script>
</body>
</html>