<?php
include_once './conexion/cone.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die('Producto no válido.');
}

$sql = "SELECT 
    cp.id,
    p.nombre_producto,
    ip.url_imagen,
    i.precio_venta,
    cp.estado,
    cp.estado_oferta,
    cp.limite_oferta,
    cp.oferta,
    (i.precio_venta * (1 - (cp.oferta / 100))) AS precio_con_descuento
FROM 
    catalogo_productos cp
JOIN 
    producto p ON cp.producto_id = p.id_producto
JOIN 
    ingreso i ON cp.ingreso_id = i.id
JOIN 
    imagenes_producto ip ON cp.imagen_id = ip.id
WHERE
    cp.id = $1
LIMIT 1;";

$result = pg_query_params($conn, $sql, [$id]);
$producto = pg_fetch_assoc($result);
if (!$producto) {
    die('Producto no encontrado.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre_producto']); ?> | Freestyle Shop</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-10 px-4 flex flex-col md:flex-row items-center md:items-start gap-10">
        <div class="w-full md:w-1/2 flex justify-center">
            <img src="<?php echo htmlspecialchars($producto['url_imagen']); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="rounded-lg shadow-lg max-w-xs md:max-w-md w-full object-cover aspect-square bg-white" />
        </div>
        <div class="w-full md:w-1/2 flex flex-col items-start">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4"><?php echo htmlspecialchars($producto['nombre_producto']); ?></h1>
            <div class="mb-4 flex flex-col gap-1">
                <span class="text-lg text-gray-400 line-through <?php echo ($producto['estado_oferta'] == 't' || $producto['estado_oferta'] == 1) ? '' : 'hidden'; ?>">S/ <?php echo number_format($producto['precio_venta'], 2); ?></span>
                <span class="text-2xl font-bold <?php echo ($producto['estado_oferta'] == 't' || $producto['estado_oferta'] == 1) ? 'text-yellow-400' : 'text-gray-900'; ?>">
                    S/ <?php echo number_format(($producto['estado_oferta'] == 't' || $producto['estado_oferta'] == 1) ? $producto['precio_con_descuento'] : $producto['precio_venta'], 2); ?>
                </span>
                <?php if ($producto['estado_oferta'] == 't' || $producto['estado_oferta'] == 1) { ?>
                    <span class="text-green-500 font-semibold">-<?php echo htmlspecialchars($producto['oferta']); ?>% OFF</span>
                    <span class="text-xs text-gray-500">Válido hasta: <?php echo date('d/m/Y', strtotime($producto['limite_oferta'])); ?></span>
                <?php } ?>
            </div>
            <div class="flex gap-4 mt-6">
                <a href="catalogo_producto.php" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded transition">Volver</a>
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded transition">Comprar</a>
            </div>
        </div>
    </div>
</body>
</html> 