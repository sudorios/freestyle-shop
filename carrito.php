<?php
session_start();
include_once './includes/head.php';
include_once './includes_client/header.php';
?>
<body class="bg-gray-100 min-h-screen">
    <main class="container mx-auto py-10 px-4 flex flex-col lg:flex-row gap-8">
        <div class="flex-1 min-w-0">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">TU CARRITO</h1>
            <div class="mb-4 p-4 bg-gray-100 rounded-lg">
                <span class="font-bold">OFERTAS CYBERNÉTICAS CON ENVÍO GRATIS</span><br>
                <span class="text-gray-600 text-sm">Los artículos de tu carrito no están reservados, realiza tu compra antes que se agoten.</span>
            </div>
            <div id="carrito-lista"></div>
        </div>
        <aside class="w-full lg:w-96 flex-shrink-0">
            <div class="lg:sticky top-24 bg-white rounded-lg shadow p-6" id="carrito-resumen">
            </div>
        </aside>
    </main>
    <?php include_once './includes/footer.php'; ?>
    <script src="assets/js/carrito.js"></script>
</body> 