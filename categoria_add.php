<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario']) || !isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include_once 'conexion/cone.php'; 
include_once 'includes/head.php';
?>

<body class="bg-black min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 w-[600px] border-2 border-black">
        <h2 class="text-3xl font-black mb-8 text-center tracking-wider">CREAR CATEGORÍA</h2>

        <form action="categoria_registrar.php" method="POST" class="space-y-6">
            <div>
                <label for="nombre" class="block text-sm font-bold uppercase tracking-wider text-black">Nombre de la Categoría</label>
                <input type="text" id="nombre" name="txtnombre" required
                    class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
            </div>

            <div>
                <label for="descripcion" class="block text-sm font-bold uppercase tracking-wider text-black">Descripción</label>
                <textarea id="descripcion" name="txtdescripcion" rows="4" required
                    class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black"></textarea>
            </div>

            <div>
                <label for="estado" class="block text-sm font-bold uppercase tracking-wider text-black">Estado</label>
                <select id="estado" name="txtestado" required
                    class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
                    <option value="1">Activa</option>
                    <option value="0">Inactiva</option>
                </select>
            </div>

            <button type="submit"
                class="w-full py-3 px-4 border-2 border-black bg-black text-white font-bold uppercase tracking-wider hover:bg-white hover:text-black transition-colors duration-200 cursor-pointer">
                Crear Categoría
            </button>
        </form>

        <p class="mt-6 text-center text-sm font-bold uppercase tracking-wider text-black">
            <a href="categoria.php" class="underline hover:no-underline">Volver al listado de categorías</a>
        </p>
    </div>
</body>

</html> 