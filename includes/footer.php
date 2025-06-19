<footer class="bg-gray-900 text-gray-300 py-6 mt-8 border-t border-gray-800">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between">
        <div class="mb-2 md:mb-0 flex items-center space-x-2">
            <span class="font-bold tracking-wider">Freestyle Shop</span>
            <span class="text-xs text-gray-500">&copy; <?php echo date('Y'); ?> Todos los derechos reservados.</span>
        </div>
        <div class="flex space-x-4">
            <a href="https://www.instagram.com/" target="_blank" class="hover:text-pink-400 transition-colors"><i class="fab fa-instagram"></i></a>
            <a href="https://www.facebook.com/" target="_blank" class="hover:text-blue-500 transition-colors"><i class="fab fa-facebook"></i></a>
            <a href="mailto:contacto@freestyle.com" class="hover:text-green-400 transition-colors"><i class="fas fa-envelope"></i></a>
        </div>
    </div>
</footer>
<script>
    const currentPath = window.location.pathname;
    const sidebarNavLinks = document.querySelectorAll('#sidebar nav a');

    sidebarNavLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath.split('/').pop()) {
            link.classList.remove('text-gray-300', 'hover:bg-gray-700', 'hover:text-white');
            link.classList.add('bg-blue-600', 'text-white');
        }
    });
</script>