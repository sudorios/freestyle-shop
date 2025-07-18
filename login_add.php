<?php 
include_once 'conexion/cone.php'; 
include_once 'includes/head.php';
?>

<body class="bg-black min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 w-[600px] border-2 border-black">
        <h2 class="text-3xl font-black mb-8 text-center tracking-wider">CREAR CUENTA</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-center font-bold uppercase tracking-wider'>
                Usuario registrado correctamente. Redirigiendo...
            </div>
            <meta http-equiv="refresh" content="2;url=login.php">
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-center font-bold uppercase tracking-wider'>
                <?php
                if ($_GET['error'] === 'correo') {
                    echo 'El correo electrónico ya está registrado.';
                } elseif ($_GET['error'] === 'nick') {
                    echo 'El nickname ya está en uso.';
                } elseif ($_GET['error'] === 'registro') {
                    echo 'Error al registrar al usuario.';
                } else {
                    echo 'Ocurrió un error.';
                }
                ?>
            </div>
        <?php endif; ?>

        <form action="views/usuario/registrar.php" method="POST" class="space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-bold uppercase tracking-wider text-black">Nombre</label>
                    <input type="text" id="name" name="txtname" required
                        class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
                </div>
                <div>
                    <label for="email" class="block text-sm font-bold uppercase tracking-wider text-black">Correo electrónico</label>
                    <input type="email" id="email" name="txtcorreo" required
                        class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
                </div>
                <div>
                    <label for="nickname" class="block text-sm font-bold uppercase tracking-wider text-black">Nickname</label>
                    <input type="text" id="nickname" name="txtnick" required
                        class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold uppercase tracking-wider text-black">Contraseña</label>
                    <input type="password" id="password" name="txtpass" required
                        class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-bold uppercase tracking-wider text-black">Teléfono</label>
                    <input type="tel" id="phone" name="txttelefono" required
                        class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
                </div>
            </div>

            <div>
                <label for="address" class="block text-sm font-bold uppercase tracking-wider text-black">Dirección</label>
                <input type="text" id="address" name="txtdireccion" required
                    class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
            </div>

            <button type="submit"
                class="w-full py-3 px-4 border-2 border-black bg-black text-white font-bold uppercase tracking-wider hover:bg-white hover:text-black transition-colors duration-200 cursor-pointer">
                Registrarse
            </button>
        </form>

        <p class="mt-6 text-center text-sm font-bold uppercase tracking-wider text-black">
            ¿Ya tienes una cuenta?
            <a href="login.php" class="underline hover:no-underline">Inicia sesión aquí</a>
        </p>
    </div>
</body>

</html>