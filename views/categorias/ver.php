<?php $esCliente = true; include_once 'includes/head.php'; include_once 'includes/header.php'; ?>
<body class="bg-gray-100 min-h-screen">
    <main class="container mx-auto py-10 px-4">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-4 uppercase tracking-wide border-b-4 border-pink-600 pb-2">
            <?php echo htmlspecialchars($productos[0]['nombre'] ?? 'Categoría'); ?>
        </h1>
        <hr class="my-6 border-gray-300">
        <form method="get" class="mb-8 flex flex-wrap gap-4 items-end bg-white p-4 rounded-lg shadow">
            <input type="hidden" name="controller" value="categoria">
            <input type="hidden" name="action" value="ver">
            <input type="hidden" name="categoria_id" value="<?php echo $categoria_id; ?>">
            <div>
                <label class="block text-sm font-bold mb-1 text-gray-700">Subcategoría</label>
                <select name="id_subcategoria" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="0">Todas</option>
                    <?php foreach ($subcategorias as $sub): ?>
                        <option value="<?php echo $sub['id_subcategoria']; ?>" <?php if ($id_subcategoria == $sub['id_subcategoria']) echo 'selected'; ?>><?php echo htmlspecialchars($sub['nombre_subcategoria']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-bold mb-1 text-gray-700">Ordenar por</label>
                <select name="orden" class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500">
                    <option value="nombre_asc" <?php if ($orden == 'nombre_asc') echo 'selected'; ?>>Nombre (A-Z)</option>
                    <option value="nombre_desc" <?php if ($orden == 'nombre_desc') echo 'selected'; ?>>Nombre (Z-A)</option>
                    <option value="precio_asc" <?php if ($orden == 'precio_asc') echo 'selected'; ?>>Precio (menor a mayor)</option>
                    <option value="precio_desc" <?php if ($orden == 'precio_desc') echo 'selected'; ?>>Precio (mayor a menor)</option>
                </select>
            </div>
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold px-6 py-2 rounded ml-2 shadow transition">Filtrar</button>
        </form>
        <hr class="mb-8 border-gray-300">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php if (count($productos) === 0): ?>
                <div class="col-span-full flex flex-col items-center justify-center py-16">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 018 0v2m-4-4a4 4 0 100-8 4 4 0 000 8zm0 0v2m0 4h.01" />
                    </svg>
                    <p class="text-gray-500 text-lg font-semibold text-center">No hay productos disponibles para los filtros seleccionados.<br>Prueba con otra subcategoría.</p>
                </div>
            <?php else: ?>
                <?php foreach ($productos as $producto): ?>
                    <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center border border-gray-200 hover:shadow-lg transition">
                        <img src="<?php echo htmlspecialchars($producto['url_imagen'] ?? 'https://via.placeholder.com/300x300?text=Producto'); ?>" alt="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" class="object-contain w-full h-64 mb-4 rounded border border-gray-100" />
                        <div class="text-lg font-bold text-gray-900 mb-2 text-center"><?php echo htmlspecialchars($producto['nombre_producto']); ?></div>
                        <div class="text-gray-700 text-sm mb-2 text-center line-clamp-3"><?php echo htmlspecialchars($producto['descripcion_producto']); ?></div>
                        <hr class="w-2/3 my-2 border-gray-200">
                        <div class="flex items-center gap-2 mb-2">
                            <?php if ($producto['oferta'] > 0): ?>
                                <span class="text-gray-400 line-through">S/ <?php echo number_format($producto['precio_venta'], 2); ?></span>
                                <span class="text-pink-600 font-bold">S/ <?php echo number_format($producto['precio_venta'] * (1 - ($producto['oferta'] / 100)), 2); ?></span>
                                <span class="text-green-500 font-semibold text-xs">-<?php echo number_format($producto['oferta'], 0); ?>% OFF</span>
                            <?php else: ?>
                                <span class="text-gray-900 font-bold text-lg">S/ <?php echo number_format($producto['precio_venta'], 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="index.php?controller=producto&action=ver&id=<?php echo $producto['id_catalogo']; ?>" class="mt-auto w-full bg-black hover:bg-gray-900 text-white font-bold py-2 rounded text-center transition">Ver producto</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <hr class="my-10 border-gray-300">
    </main>
    <?php include_once 'includes/footer.php'; ?>
</body>
</html> 