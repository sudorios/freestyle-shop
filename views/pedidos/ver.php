<?php
if (!isset($pedido)) {
    echo '<div class="text-red-600 font-bold">Pedido no encontrado.</div>';
    return;
}
?>
<!DOCTYPE html>
<html lang="es">
<?php include_once __DIR__ . '/../../includes/head.php'; ?>
<body class="ml-72 mt-20 bg-gray-100">
    <?php include_once __DIR__ . '/../../includes/header.php'; ?>
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-6 flex items-center"><i class="fas fa-search text-blue-500 mr-2"></i>Detalle del Pedido #<?php echo htmlspecialchars($pedido['id_pedido']); ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Cliente</label>
                    <div class="bg-gray-100 rounded px-3 py-2"><?php echo htmlspecialchars($pedido['nombre_usuario'] ?? '-'); ?></div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Email</label>
                    <div class="bg-gray-100 rounded px-3 py-2"><?php echo htmlspecialchars($pedido['email_usuario'] ?? '-'); ?></div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Fecha</label>
                    <div class="bg-gray-100 rounded px-3 py-2"><?php echo htmlspecialchars($pedido['fecha']); ?></div>
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Estado</label>
                    <div class="bg-gray-100 rounded px-3 py-2 uppercase"><?php echo htmlspecialchars($pedido['estado']); ?></div>
                </div>
            </div>
            <h3 class="text-xl font-bold mb-4">Productos</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 mb-6">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Talla</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio Unitario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($detalles as $item): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($item['nombre_producto']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($item['talla_producto']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($item['cantidad']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">S/ <?php echo number_format($item['precio_unitario'], 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">S/ <?php echo number_format($item['subtotal'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($detalles)): ?>
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Sin productos</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end">
                <div class="text-xl font-bold">Total: S/ <?php echo number_format($pedido['total'], 2); ?></div>
            </div>
            <div class="mt-8 flex justify-between">
                <a href="index.php?controller=pedido&action=listar" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Volver al listado</a>
                <a href="index.php?controller=pedido&action=exportarPDF&id=<?php echo urlencode($pedido['id_pedido']); ?>" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                </a>
                <a href="index.php?controller=pedido&action=generarBoletaPDF&id=<?php echo urlencode($pedido['id_pedido']); ?>" target="_blank" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-file-invoice mr-2"></i> Boleta PDF
                </a>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html> 