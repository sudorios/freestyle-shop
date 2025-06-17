<?php
session_start();
?>
<header class="bg-gray-900 text-white fixed top-0 left-0 right-0 z-50">
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
                        <i class="fas fa-user-circle text-lg"></i>
                        <span class="hidden md:inline"><?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario'; ?></span>
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
        </div>
    </div>
</header>
<aside id="sidebar" class="fixed left-0 top-16 h-full w-64 bg-gray-800 text-white z-40">
    <div class="p-4">
        <nav class="space-y-2">
            <div class="space-y-1">
                <a href="productos_admin.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                    <i class="fas fa-box w-5 h-5 mr-3"></i>
                    <span class="font-medium">Productos</span>
                </a>
                <div class="ml-8 space-y-1">
                    <a href="agregar_producto.php" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        <span>Agregar Producto</span>
                    </a>
                    <a href="categorias.php" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-tags w-4 h-4 mr-2"></i>
                        <span>Categorías</span>
                    </a>
                </div>
            </div>
            <div class="space-y-1">
                <a href="pedidos_admin.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                    <i class="fas fa-shopping-bag w-5 h-5 mr-3"></i>
                    <span class="font-medium">Pedidos</span>
                </a>
                <div class="ml-8 space-y-1">
                    <a href="pedidos_pendientes.php" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-clock w-4 h-4 mr-2"></i>
                        <span>Pendientes</span>
                    </a>
                    <a href="pedidos_completados.php" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-check-circle w-4 h-4 mr-2"></i>
                        <span>Completados</span>
                    </a>
                </div>
            </div>
            
            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
            <div class="space-y-1">
                <a href="usuario.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                    <i class="fas fa-users w-5 h-5 mr-3"></i>
                    <span class="font-medium">Usuarios</span>
                </a>
                <div class="ml-8 space-y-1">
                    <a href="clientes.php" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-user w-4 h-4 mr-2"></i>
                        <span>Clientes</span>
                    </a>
                    <a href="administradores.php" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-user-shield w-4 h-4 mr-2"></i>
                        <span>Administradores</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="space-y-1">
                <a href="reportes.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                    <i class="fas fa-chart-bar w-5 h-5 mr-3"></i>
                    <span class="font-medium">Reportes</span>
                </a>
                <div class="ml-8 space-y-1">
                    <a href="ventas.php" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-chart-line w-4 h-4 mr-2"></i>
                        <span>Ventas</span>
                    </a>
                    <a href="inventario.php" class="flex items-center px-4 py-2 text-sm text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-warehouse w-4 h-4 mr-2"></i>
                        <span>Inventario</span>
                    </a>
                </div>
            </div>                
            <div class="pt-4 border-t border-gray-700">
                <a href="configuracion.php" class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white rounded-lg transition-colors">
                    <i class="fas fa-cog w-5 h-5 mr-3"></i>
                    <span class="font-medium">Configuración</span>
                </a>
            </div>
        </nav>
    </div>
</aside>

<main id="main-content" class="ml-64 pt-16">
    <div class="p-6">
    </div>
</main>
