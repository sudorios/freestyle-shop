<?php
include_once 'includes/head.php';
$error = isset($_GET['error']) && $_GET['error'] == 1;
?>

<body class="bg-black min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 w-96 border-2 <?php echo $error ? 'border-red-500' : 'border-black'; ?>" <?php if ($error) echo 'style="box-shadow: inset 0 0 16px 2px #ef4444;"'; ?>>
        <h2 class="text-3xl font-black mb-8 text-center tracking-wider">INICIAR SESIÓN</h2>

        <form action="conexion/validar.php" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-bold uppercase tracking-wider text-black">Correo
                    electrónico o nickname</label>
                <input type="text" id="email" name="txtusu" required
                    class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
            </div>

            <div>
                <label for="password"
                    class="block text-sm font-bold uppercase tracking-wider text-black">Contraseña</label>
                <input type="password" id="password" name="txtpass" required
                    class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
            </div>

            <div class="flex items-center justify-between">
                <a href="#" class="text-sm font-bold uppercase tracking-wider text-black hover:underline">¿Olvidaste tu
                    contraseña?</a>
            </div>

            <button type="submit"
                class="w-full py-3 px-4 border-2 border-black bg-black text-white font-bold uppercase tracking-wider hover:bg-white hover:text-black transition-colors duration-200 cursor-pointer">
                Iniciar sesión
            </button>
        </form>

        <?php if ($error): ?>
            <div
                id="login-error-msg"
                class="flex items-center justify-center gap-2 text-red-600 text-center font-bold mt-4 mb-4 uppercase border-t-2 border-b-2 border-red-500">
                Usuario o contraseña incorrectos
            </div>
        <?php endif; ?>

        <p class="mt-6 text-center text-sm font-bold uppercase tracking-wider text-black">
            ¿No tienes una cuenta?
            <a href="login_add.php" class="underline hover:no-underline">Regístrate aquí</a>
        </p>
    </div>
    <?php if ($error): ?>
        <meta http-equiv="refresh" content="3;url=login.php">
    <?php endif; ?>
</body>

</html>