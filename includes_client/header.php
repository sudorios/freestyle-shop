
<header class="bg-black border-b-2 border-pink-600 shadow-lg sticky top-0 z-50">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <a href="/" class="flex items-center space-x-3">
      <span class="bg-pink-600 text-black rounded-full w-12 h-12 flex items-center justify-center text-3xl font-black border-2 border-pink-600">FS</span>
      <span class="text-2xl font-black tracking-widest uppercase text-white">Freestyle</span>
    </a>
    <nav class="hidden md:flex space-x-8 text-lg font-bold uppercase tracking-wider">
      <a href="/" class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Inicio</a>
      <a href="#novedades" class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Nosotros</a>
      <div class="relative group">
        <button class="uppercase hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition flex items-center gap-1">Categoría
          <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div class="absolute left-0 mt-2 w-52 bg-gray-900 border-2 border-pink-600 rounded shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none group-hover:pointer-events-auto transition-opacity z-50">
          <a href="#camisetas" class="block px-4 py-2 text-white hover:bg-pink-600 hover:text-black transition">Camisetas</a>
          <a href="#hoodies" class="block px-4 py-2 text-white hover:bg-pink-600 hover:text-black transition">Hoodies</a>
          <a href="#pantalones" class="block px-4 py-2 text-white hover:bg-pink-600 hover:text-black transition">Pantalones</a>
          <a href="#zapatillas" class="block px-4 py-2 text-white hover:bg-pink-600 hover:text-black transition">Zapatillas</a>
          <a href="#gorras" class="block px-4 py-2 text-white hover:bg-pink-600 hover:text-black transition">Gorras</a>
          <a href="#accesorios" class="block px-4 py-2 text-white hover:bg-pink-600 hover:text-black transition">Accesorios</a>
        </div>
      </div>
      <a href="#accesorios" class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Accesorios</a>
    </nav>
    <div class="flex items-center space-x-5">
      <a href="carrito.php" class="relative group">
        <i class="fas fa-shopping-cart w-7 h-7 text-white group-hover:text-pink-400 transition text-2xl"></i>
        <span class="absolute -top-2 -right-2 bg-pink-600 text-xs rounded-full px-1.5 py-0.5 font-bold border-2 border-black">0</span>
      </a>
      <a href="#perfil" class="group">
        <i class="fas fa-user-circle w-7 h-7 text-white group-hover:text-pink-400 transition text-2xl"></i>
      </a>
      <button id="menu-movil-btn" class="md:hidden focus:outline-none ml-2 border-2 border-pink-600 rounded p-1 bg-black">
        <i class="fas fa-bars text-white text-2xl"></i>
      </button>
    </div>
  </div>
  <div id="menu-movil" class="md:hidden bg-black border-t-2 border-pink-600 px-4 py-3 hidden">
    <nav class="flex flex-col space-y-3 text-lg font-bold uppercase tracking-wider">
      <a href="/" class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Inicio</a>
      <a href="#novedades" class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Nosotros</a>
      <div class="relative group">
        <button class="w-full text-left uppercase hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition flex items-center gap-1">Categoría
          <i class="fas fa-chevron-down ml-1"></i>
        </button>
        <div class="ml-4 mt-1 flex flex-col space-y-1">
          <a href="#camisetas" class="block px-2 py-1 text-white hover:bg-pink-600 hover:text-black rounded transition">Camisetas</a>
          <a href="#hoodies" class="block px-2 py-1 text-white hover:bg-pink-600 hover:text-black rounded transition">Hoodies</a>
          <a href="#pantalones" class="block px-2 py-1 text-white hover:bg-pink-600 hover:text-black rounded transition">Pantalones</a>
          <a href="#zapatillas" class="block px-2 py-1 text-white hover:bg-pink-600 hover:text-black rounded transition">Zapatillas</a>
          <a href="#gorras" class="block px-2 py-1 text-white hover:bg-pink-600 hover:text-black rounded transition">Gorras</a>
          <a href="#accesorios" class="block px-2 py-1 text-white hover:bg-pink-600 hover:text-black rounded transition">Accesorios</a>
        </div>
      </div>
      <a href="#accesorios" class="hover:text-pink-400 text-white border-b-2 border-transparent hover:border-pink-600 transition">Accesorios</a>
    </nav>
  </div>
</header>
<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
<script>
  // Menú móvil toggle
  document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('menu-movil-btn');
    const menu = document.getElementById('menu-movil');
    if(btn && menu) {
      btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
      });
    }
  });

function actualizarContadorCarrito() {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    let total = carrito.reduce((sum, item) => sum + (parseInt(item.cantidad) || 0), 0);
    const badge = document.querySelector('.fa-shopping-cart').parentElement.querySelector('span');
    if (badge) {
        badge.textContent = total;
        badge.style.display = total > 0 ? 'inline-block' : 'none';
    }
}
actualizarContadorCarrito();
window.addEventListener('storage', actualizarContadorCarrito);
</script>
