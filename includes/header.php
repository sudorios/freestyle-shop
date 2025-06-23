<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="bg-gray-900 text-white fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center space-x-4">
                <a href="index.php" class="text-xl font-black tracking-wider">
                    FREESTYLE ADMIN
                </a>
                <span class="text-sm text-gray-400">| Panel de Control</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="perfil.php">
                    <div class="flex items-center space-x-2 text-sm font-bold uppercase tracking-wider">
                        <i class="fas fa-user-circle text-lg"></i>
                        <span class="hidden md:inline"><?php echo isset($_SESSION['usuario']) ? $_SESSION['usuario'] : 'Usuario'; ?></span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</header>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        if ($('#userMenuButton').on('click')) {
            $('#userMenuDropdown').toggleClass('hidden');
        } else {
            $('#userMenuDropdown').addClass('hidden');
        }
    });
</script>
<nav class="bg-gray-800 h-screen fixed top-0 left-0 min-w-[250px] py-6 px-4 overflow-auto z-40">
    <a href="index.php" class="flex items-center mb-6">
        <i class="fas fa-store text-2xl text-blue-400 mr-2"></i>
        <span class="text-xl font-black tracking-wider text-white">FREESTYLE ADMIN</span>
    </a>
    <div>
        <div class="flex items-center cursor-pointer group collapsible-toggle">
            <i class="fas fa-box text-white w-5 h-5 mr-2"></i>
            <h6 class="text-white group-hover:text-blue-400 text-[15px] font-semibold px-2 flex-1">Productos</h6>
            <i class="fas fa-chevron-down text-gray-400 arrow transition-all rotate-90"></i>
        </div>
        <ul class="space-y-1 mt-2 pl-7 max-h-[500px] overflow-hidden transition-all duration-300">
            <li><a href="producto.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-box-open w-4 h-4 mr-2"></i>Producto</a></li>
            <li><a href="categoria.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-tags w-4 h-4 mr-2"></i>Categoría</a></li>
            <li><a href="subcategoria.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-plus w-4 h-4 mr-2"></i>Subcategoría</a></li>
        </ul>
        <div class="flex items-center cursor-pointer group collapsible-toggle mt-4">
            <i class="fas fa-store text-white w-5 h-5 mr-2"></i>
            <h6 class="text-white group-hover:text-blue-400 text-[15px] font-semibold px-2 flex-1">Sucursales</h6>
            <i class="fas fa-chevron-down text-gray-400 arrow transition-all rotate-90"></i>
        </div>
        <ul class="space-y-1 mt-2 pl-7 max-h-[500px] overflow-hidden transition-all duration-300">
            <li><a href="sucursales.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-store w-4 h-4 mr-2"></i>Gestionar Sucursales</a></li>
        </ul>
        <div class="flex items-center cursor-pointer group collapsible-toggle mt-4">
            <i class="fas fa-clipboard-list text-white w-5 h-5 mr-2"></i>
            <h6 class="text-white group-hover:text-blue-400 text-[15px] font-semibold px-2 flex-1">Movimientos</h6>
            <i class="fas fa-chevron-down text-gray-400 arrow transition-all rotate-90"></i>
        </div>
        <ul class="space-y-1 mt-2 pl-7 max-h-[500px] overflow-hidden transition-all duration-300">
            <li><a href="kardex.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-clipboard-list w-4 h-4 mr-2"></i>Movimientos</a></li>
            <li><a href="ingreso.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-truck w-4 h-4 mr-2"></i>Ingreso de Productos</a></li>
            <li><a href="transferencia.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-exchange-alt w-4 h-4 mr-2"></i>Transferencias</a></li>
            <li><a href="inventario.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-warehouse w-4 h-4 mr-2"></i>Inventario</a></li>
        </ul>
        <div class="flex items-center cursor-pointer group collapsible-toggle mt-4">
            <i class="fas fa-shopping-bag text-white w-5 h-5 mr-2"></i>
            <h6 class="text-white group-hover:text-blue-400 text-[15px] font-semibold px-2 flex-1">Pedidos</h6>
            <i class="fas fa-chevron-down text-gray-400 arrow transition-all rotate-90"></i>
        </div>
        <ul class="space-y-1 mt-2 pl-7 max-h-[500px] overflow-hidden transition-all duration-300">
            <li><a href="pedidos_admin.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-list w-4 h-4 mr-2"></i>Todos los Pedidos</a></li>
            <li><a href="pedidos_pendientes.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-clock w-4 h-4 mr-2"></i>Pendientes</a></li>
            <li><a href="pedidos_completados.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-check-circle w-4 h-4 mr-2"></i>Completados</a></li>
        </ul>
        <div class="flex items-center cursor-pointer group collapsible-toggle mt-4">
            <i class="fas fa-users text-white w-5 h-5 mr-2"></i>
            <h6 class="text-white group-hover:text-blue-400 text-[15px] font-semibold px-2 flex-1">Usuarios</h6>
            <i class="fas fa-chevron-down text-gray-400 arrow transition-all rotate-90"></i>
        </div>
        <ul class="space-y-1 mt-2 pl-7 max-h-[500px] overflow-hidden transition-all duration-300">
            <li><a href="usuario.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-users w-4 h-4 mr-2"></i>Usuarios</a></li>
            <li><a href="clientes.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-user w-4 h-4 mr-2"></i>Clientes</a></li>
            <li><a href="administradores.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-user-shield w-4 h-4 mr-2"></i>Administradores</a></li>
        </ul>
        <div class="flex items-center cursor-pointer group collapsible-toggle mt-4">
            <i class="fas fa-chart-bar text-white w-5 h-5 mr-2"></i>
            <h6 class="text-white group-hover:text-blue-400 text-[15px] font-semibold px-2 flex-1">Reportes</h6>
            <i class="fas fa-chevron-down text-gray-400 arrow transition-all rotate-90"></i>
        </div>
        <ul class="space-y-1 mt-2 pl-7 max-h-[500px] overflow-hidden transition-all duration-300">
            <li><a href="reportes.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-chart-bar w-4 h-4 mr-2"></i>Reportes</a></li>
            <li><a href="ventas.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-chart-line w-4 h-4 mr-2"></i>Ventas</a></li>
            <li><a href="inventario.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-warehouse w-4 h-4 mr-2"></i>Inventario</a></li>
        </ul>
        <div class="flex items-center cursor-pointer group collapsible-toggle mt-4">
            <i class="fas fa-cog text-white w-5 h-5 mr-2"></i>
            <h6 class="text-white group-hover:text-blue-400 text-[15px] font-semibold px-2 flex-1">Configuración</h6>
            <i class="fas fa-chevron-down text-gray-400 arrow transition-all rotate-90"></i>
        </div>
        <ul class="space-y-1 mt-2 pl-7 max-h-[500px] overflow-hidden transition-all duration-300">
            <li><a href="perfil.php" class="flex items-center text-gray-300 hover:text-blue-400 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-user-cog w-4 h-4 mr-2"></i>Mi Perfil</a></li>
            <li><a id="cerrarSesionBtn" class="flex items-center text-red-600 hover:text-red-700 font-medium transition-all text-[15px] hover:bg-gray-800 rounded-md px-3 py-2"><i class="fas fa-sign-out-alt w-4 h-4 mr-2"></i>Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>

<div id="modalCerrarSesion" class="fixed inset-0 bg-black opacity-75 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full mx-auto flex flex-col items-center">
        <h2 class="text-xl font-bold mb-4 text-gray-800">¿Cerrar sesión?</h2>
        <p class="mb-6 text-gray-600">¿Estás seguro que deseas cerrar sesión?</p>
        <div class="flex justify-end space-x-3 w-full">
            <button id="cancelarCerrarSesion" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Cancelar</button>
            <a href="cerrar_sesion.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Cerrar sesión</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".collapsible-toggle").forEach((toggle) => {
            toggle.addEventListener("click", function() {
                let menu = this.nextElementSibling; // The submenu <ul>
                let arrowIcon = this.querySelector(".arrow");

                if (menu.offsetHeight !== 0) {
                    menu.style.maxHeight = '0px';
                    arrowIcon.classList.remove("rotate-90");
                } else {
                    menu.style.maxHeight = menu.scrollHeight + "px";
                    arrowIcon.classList.add("rotate-90");
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var cerrarSesionBtn = document.getElementById('cerrarSesionBtn');
        var modalCerrarSesion = document.getElementById('modalCerrarSesion');
        var cancelarCerrarSesion = document.getElementById('cancelarCerrarSesion');
        if (cerrarSesionBtn && modalCerrarSesion && cancelarCerrarSesion) {
            cerrarSesionBtn.addEventListener('click', function(e) {
                e.preventDefault();
                modalCerrarSesion.classList.remove('hidden');
                modalCerrarSesion.classList.add('flex');
            });
            cancelarCerrarSesion.addEventListener('click', function() {
                modalCerrarSesion.classList.add('hidden');
                modalCerrarSesion.classList.remove('flex');
            });
            modalCerrarSesion.addEventListener('click', function(e) {
                if (e.target === modalCerrarSesion) {
                    modalCerrarSesion.classList.add('hidden');
                    modalCerrarSesion.classList.remove('flex');
                }
            });
        }
    });
</script>
</main>