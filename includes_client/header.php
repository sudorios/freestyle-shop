
<header class="bg-black text-white shadow-lg sticky top-0 z-50">
  <div class="container mx-auto px-4 py-3 flex items-center justify-between">
    <a href="/" class="flex items-center space-x-3">
      <span class="bg-gradient-to-tr from-pink-500 to-yellow-400 rounded-full w-10 h-10 flex items-center justify-center text-2xl font-black">FS</span>
      <span class="text-2xl font-extrabold tracking-widest uppercase">Freestyle</span>
    </a>
    <nav class="hidden md:flex space-x-8 text-lg font-semibold">
      <a href="/" class="hover:text-yellow-400 transition">Inicio</a>
      <a href="#novedades" class="hover:text-yellow-400 transition">Novedades</a>
      <a href="#hombres" class="hover:text-yellow-400 transition">Hombres</a>
      <a href="#mujeres" class="hover:text-yellow-400 transition">Mujeres</a>
      <a href="#accesorios" class="hover:text-yellow-400 transition">Accesorios</a>
      <a href="#ofertas" class="hover:text-yellow-400 transition">Ofertas</a>
    </nav>
    <div class="flex items-center space-x-5">
      <a href="#carrito" class="relative group">
        <svg class="w-7 h-7 text-white group-hover:text-yellow-400 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m13-9l2 9m-5-9V6a2 2 0 10-4 0v3"/></svg>
        <span class="absolute -top-2 -right-2 bg-pink-500 text-xs rounded-full px-1.5 py-0.5 font-bold">0</span>
      </a>
      <a href="#perfil" class="group">
        <svg class="w-7 h-7 text-white group-hover:text-yellow-400 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      </a>
      <button id="menu-movil-btn" class="md:hidden focus:outline-none ml-2">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </div>
  <div id="menu-movil" class="md:hidden bg-black border-t border-gray-800 px-4 py-3 hidden">
    <nav class="flex flex-col space-y-3 text-lg font-semibold">
      <a href="/" class="hover:text-yellow-400 transition">Inicio</a>
      <a href="#novedades" class="hover:text-yellow-400 transition">Novedades</a>
      <a href="#hombres" class="hover:text-yellow-400 transition">Hombres</a>
      <a href="#mujeres" class="hover:text-yellow-400 transition">Mujeres</a>
      <a href="#accesorios" class="hover:text-yellow-400 transition">Accesorios</a>
      <a href="#ofertas" class="hover:text-yellow-400 transition">Ofertas</a>
    </nav>
  </div>
</header>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('menu-movil-btn');
    const menu = document.getElementById('menu-movil');
    if(btn && menu) {
      btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
      });
    }
  });
</script>
