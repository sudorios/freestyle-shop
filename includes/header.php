<?php
session_start();
?>
<header class="bg-gray-900 text-white">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-xl font-black tracking-wider">
                    FREESTYLE ADMIN
                </a>
                <span class="text-sm text-gray-400">| Panel de Control</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative group">
                    <button class="flex items-center space-x-2 text-sm font-bold uppercase tracking-wider hover:text-gray-300 transition-colors">
                        <span class="hidden md:inline">Admin</span>
                    </button>
                    <div class="absolute right-0 w-48 mt-2 py-2 bg-white text-black rounded-lg shadow-xl hidden group-hover:block">
                        <a href="perfil_admin.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                            <i class="fas fa-user-cog mr-2"></i>Mi Perfil
                        </a>
                        <a href="configuracion.php" class="block px-4 py-2 text-sm hover:bg-gray-100">
                            <i class="fas fa-cog mr-2"></i>Configuración
                        </a>
                        <div class="border-t border-gray-200"></div>
                        <a href="cerrar_sesion.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>

            <button class="md:hidden text-white focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <div class="md:hidden hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="dashboard.php" class="block px-3 py-2 text-sm font-bold uppercase tracking-wider hover:bg-gray-800">
                <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
            </a>
            <a href="productos_admin.php" class="block px-3 py-2 text-sm font-bold uppercase tracking-wider hover:bg-gray-800">
                <i class="fas fa-box mr-2"></i>Productos
            </a>
            <a href="pedidos_admin.php" class="block px-3 py-2 text-sm font-bold uppercase tracking-wider hover:bg-gray-800">
                <i class="fas fa-shopping-bag mr-2"></i>Pedidos
            </a>
            <a href="usuarios_admin.php" class="block px-3 py-2 text-sm font-bold uppercase tracking-wider hover:bg-gray-800">
                <i class="fas fa-users mr-2"></i>Usuarios
            </a>
            <a href="reportes.php" class="block px-3 py-2 text-sm font-bold uppercase tracking-wider hover:bg-gray-800">
                <i class="fas fa-chart-bar mr-2"></i>Reportes
            </a>
        </div>
    </div>
</header>

<script>
    document.querySelector('button.md\\:hidden').addEventListener('click', function() {
        document.querySelector('.md\\:hidden.hidden').classList.toggle('hidden');
    });
</script>
