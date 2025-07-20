
<!DOCTYPE html>
<html lang="es">
<?php include_once __DIR__ . '/../../includes/head.php'; ?>
<body class="bg-black min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 w-96 border-2 <?php echo (isset($error) && $error ? 'border-red-500' : 'border-black'); ?>" <?php if (isset($error) && $error) echo 'style="box-shadow: inset 0 0 16px 2px #ef4444;"'; ?>>
        <h2 class="text-3xl font-black mb-8 text-center tracking-wider">INICIAR SESIÓN</h2>

        <?php if ((isset($_GET['success']) && $_GET['success'] == 1) || (isset($success) && $success == 1)): ?>
            <div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-center font-bold uppercase tracking-wider'>
                ¡Usuario registrado correctamente! Ahora puedes iniciar sesión.
            </div>
        <?php endif; ?>

        <form action="index.php?controller=usuario&action=validarLogin" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-bold uppercase tracking-wider text-black">Correo electrónico o nickname</label>
                <input type="text" id="email" name="txtusu" required
                    class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
            </div>
            <div>
                <label for="password" class="block text-sm font-bold uppercase tracking-wider text-black">Contraseña</label>
                <input type="password" id="password" name="txtpass" required
                    class="mt-1 block w-full px-3 py-3 border-2 border-black bg-white focus:outline-none focus:border-black">
            </div>
            <div class="flex items-center justify-between">
                <a href="#" class="text-sm font-bold uppercase tracking-wider text-black hover:underline">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit"
                class="w-full py-3 px-4 border-2 border-black bg-black text-white font-bold uppercase tracking-wider hover:bg-white hover:text-black transition-colors duration-200 cursor-pointer">
                Iniciar sesión
            </button>
        </form>
        <?php if (isset($error) && $error): ?>
            <div
                id="login-error-msg"
                class="flex items-center justify-center gap-2 text-red-600 text-center font-bold mt-4 mb-4 uppercase border-t-2 border-b-2 border-red-500">
                Usuario o contraseña incorrectos
            </div>
        <?php endif; ?>
        <p class="mt-6 text-center text-sm font-bold uppercase tracking-wider text-black">
            ¿No tienes una cuenta?
            <a href="views/usuarios/registro.php" class="underline hover:no-underline">Regístrate aquí</a>
        </p>
    </div>
</body>
</html> 