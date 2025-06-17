        </div>
    </main>

    <script>
        // Marcar enlace activo en el sidebar
        const currentPath = window.location.pathname;
        const sidebarNavLinks = document.querySelectorAll('#sidebar nav a');
        
        sidebarNavLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath.split('/').pop()) {
                link.classList.remove('text-gray-300', 'hover:bg-gray-700', 'hover:text-white');
                link.classList.add('bg-blue-600', 'text-white');
            }
        });
    </script>
</body>
</html> 