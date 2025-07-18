<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include_once './conexion/cone.php';
$usuario_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$session_id = session_id();
if ($usuario_id) {
  $sql = "SELECT id FROM carrito WHERE usuario_id = $1";
  $params = [$usuario_id];
} else {
  $sql = "SELECT id FROM carrito WHERE session_id = $1";
  $params = [$session_id];
}
$result = pg_query_params($conn, $sql, $params);
$row = pg_fetch_assoc($result);
$carrito_id = $row ? $row['id'] : null;
$total_items = 0;
if ($carrito_id) {
  $sql = "SELECT SUM(cantidad) AS total FROM carrito_items WHERE carrito_id = $1 AND estado = 'activo'";
  $res = pg_query_params($conn, $sql, [$carrito_id]);
  $row = pg_fetch_assoc($res);
  $total_items = $row && $row['total'] ? (int) $row['total'] : 0;
}
$sql = "SELECT id_categoria, nombre_categoria
FROM categoria c
WHERE estado_categoria = true
  AND id_categoria > 0
  AND EXISTS (
    SELECT 1
    FROM producto p
    JOIN catalogo_productos cp ON cp.producto_id = p.id_producto
    JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria
    WHERE s.id_categoria = c.id_categoria
      AND cp.sucursal_id = 7
      AND (cp.estado = true OR cp.estado = 't')
  )
ORDER BY nombre_categoria ASC";
$res = pg_query($conn, $sql);
$categorias = [];
while ($row = pg_fetch_assoc($res)) {
  $categorias[] = $row;
}
?>
<header class="bg-black border-b-2 border-pink-600 shadow-lg sticky top-0 z-50">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <a href="index.php" class="flex items-center space-x-2 sm:space-x-3">
      <img src="assets/images/icono.jpg" alt="Logo Freestyle" class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover border-2 bg-white" />
      <span class="text-lg sm:text-xl lg:text-2xl font-black tracking-widest uppercase text-white">Freestyle</span>
    </a>
    
    <nav class="hidden lg:flex space-x-6 xl:space-x-8 text-base lg:text-lg font-bold uppercase tracking-wider">
      <a href="index.php"
        class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Inicio</a>
      <a href="nosotros.php"
        class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Nosotros</a>
      <div class="relative group">
        <button
          class="uppercase hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition flex items-center gap-1">
          Categorías
          <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div
          class="absolute left-0 mt-2 w-48 lg:w-52 bg-gray-900 border-2 border-pink-600 rounded shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity z-50">
          <?php foreach ($categorias as $cat): ?>
            <a href="vista_categorias.php?id_categoria=<?php echo $cat['id_categoria']; ?>"
              class="block px-4 py-2 text-white hover:bg-pink-600 hover:text-black transition">
              <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </nav>
    
    <div class="flex items-center space-x-3 sm:space-x-4 lg:space-x-5">
      <a href="carrito.php" class="relative group">
        <i class="fas fa-shopping-cart text-white group-hover:text-pink-400 transition text-xl sm:text-2xl"></i>
        <span id="carrito-contador"
          class="absolute -top-2 -right-2 bg-pink-600 text-xs rounded-full px-1.5 py-0.5 font-bold border-2 border-black"><?php echo $total_items; ?></span>
      </a>
      
      <?php if (isset($_SESSION['usuario'])): ?>
        <span class="flex items-center space-x-2 text-white font-bold uppercase tracking-wider">
          <i class="fas fa-user-circle text-white group-hover:text-pink-400 transition text-xl sm:text-2xl"></i>
          <a href="perfil.php" class="hidden md:inline hover:text-pink-400 transition underline text-sm lg:text-base">
            <?php echo htmlspecialchars($_SESSION['usuario']); ?>
          </a>
          <a href="cerrar_sesion.php" title="Cerrar sesión"
            class="inline-flex items-center justify-center text-pink-600 hover:text-pink-700 bg-white hover:bg-gray-100 rounded-full p-1.5 sm:p-2 transition"
            style="font-size: 1rem;">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </span>
      <?php else: ?>
        <a href="login.php" class="group">
          <i class="fas fa-user-circle text-white group-hover:text-pink-400 transition text-xl sm:text-2xl"></i>
        </a>
      <?php endif; ?>
      
      <button id="menu-movil-btn"
        class="lg:hidden focus:outline-none border-2 border-pink-600 rounded p-1 bg-black">
        <i class="fas fa-bars text-white text-xl sm:text-2xl"></i>
      </button>
    </div>
  </div>
  <div id="menu-movil" class="lg:hidden bg-black border-t-2 border-pink-600 px-4 py-3 hidden">
    <nav class="flex flex-col space-y-3 text-lg font-bold uppercase tracking-wider">
      <a href="index.php"
        class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Inicio</a>
      <a href="nosotros.php"
        class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Nosotros</a>
      <div class="relative">
        <button id="categorias-movil-btn"
          class="w-full text-left uppercase hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition flex items-center justify-between">
          Categorías
          <i class="fas fa-chevron-down transition-transform" id="categorias-icon"></i>
        </button>
        <div id="categorias-movil" class="ml-4 mt-1 flex flex-col space-y-1 hidden">
          <?php foreach ($categorias as $cat): ?>
            <a href="vista_categorias.php?id_categoria=<?php echo $cat['id_categoria']; ?>"
              class="block px-2 py-1 text-white hover:bg-pink-600 hover:text-black rounded transition">
              <?php echo htmlspecialchars($cat['nombre_categoria']); ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
      <a href="carrito.php" class="flex items-center space-x-2 hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">
        <i class="fas fa-shopping-cart"></i>
        <span>Carrito</span>
        <span id="carrito-contador-movil"
          class="bg-pink-600 text-xs rounded-full px-1.5 py-0.5 font-bold border-2 border-black"><?php echo $total_items; ?></span>
      </a>
      <?php if (isset($_SESSION['usuario'])): ?>
        <a href="perfil.php" class="flex items-center space-x-2 hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">
          <i class="fas fa-user-circle"></i>
          <span><?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
        </a>
        <a href="cerrar_sesion.php" class="flex items-center space-x-2 hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">
          <i class="fas fa-sign-out-alt"></i>
          <span>Cerrar Sesión</span>
        </a>
      <?php else: ?>
        <a href="login.php" class="flex items-center space-x-2 hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">
          <i class="fas fa-user-circle"></i>
          <span>Iniciar Sesión</span>
        </a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<script src="assets/js/carrito.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('menu-movil-btn');
    const menu = document.getElementById('menu-movil');
    
    if (btn && menu) {
      btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        if (menu.classList.contains('hidden')) {
          const categoriasMovil = document.getElementById('categorias-movil');
          const categoriasIcon = document.getElementById('categorias-icon');
          if (categoriasMovil) {
            categoriasMovil.classList.add('hidden');
            categoriasIcon.style.transform = 'rotate(0deg)';
          }
        }
      });
    }

    const categoriasBtn = document.getElementById('categorias-movil-btn');
    const categoriasMovil = document.getElementById('categorias-movil');
    const categoriasIcon = document.getElementById('categorias-icon');
    
    if (categoriasBtn && categoriasMovil) {
      categoriasBtn.addEventListener('click', () => {
        categoriasMovil.classList.toggle('hidden');
        if (categoriasMovil.classList.contains('hidden')) {
          categoriasIcon.style.transform = 'rotate(0deg)';
        } else {
          categoriasIcon.style.transform = 'rotate(180deg)';
        }
      });
    }

    document.addEventListener('click', (e) => {
      if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add('hidden');
        if (categoriasMovil) {
          categoriasMovil.classList.add('hidden');
          categoriasIcon.style.transform = 'rotate(0deg)';
        }
      }
    });

    actualizarContadorCarritoAjax();
    
    window.actualizarContadorCarritoAjax = function() {
      fetch('views/carrito/carrito_contador.php')
        .then(response => response.json())
        .then(data => {
          const contadorDesktop = document.getElementById('carrito-contador');
          const contadorMovil = document.getElementById('carrito-contador-movil');
          
          if (contadorDesktop) {
            contadorDesktop.textContent = data.total || 0;
          }
          if (contadorMovil) {
            contadorMovil.textContent = data.total || 0;
          }
        })
        .catch(error => console.error('Error actualizando contador:', error));
    };
  });
</script>