<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once './includes/head.php'; ?>
<body id="main-content" class="ml-72 mt-20">
<?php include_once './includes/header.php'; ?>
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
<script src="/freestyle-shop/assets/js/dashboard.js?v=1"></script>
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
<?php include_once './includes/footer.php'; ?>
</body>
</html> 