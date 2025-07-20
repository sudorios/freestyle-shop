<div id="modalBackground" class="fixed inset-0 bg-black opacity-75 hidden z-20"></div>
<div id="modalPassword" class="fixed inset-0 flex items-center justify-center hidden z-30">
    <div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-auto flex flex-col items-center">
        <h3 class="text-lg font-medium text-gray-900 mb-6">Cambiar Contraseña</h3>
        
        <form id="formCambiarPassword" action="index.php?controller=usuario&action=cambiarPassword" method="POST" class="w-full space-y-4">
            <input type="hidden" id="password_id" name="id_usuario">

            <div class="mb-4">
                <label for="password_nueva" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                <input type="password" id="password_nueva" name="password_nueva" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div class="mb-4">
                <label for="password_confirmar" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                <input type="password" id="password_confirmar" name="password_confirmar" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div class="flex justify-between space-x-3">
                <button type="button" onclick="cerrarModalPassword()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition-all duration-300">
                    Cancelar
                </button>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded transition-all duration-300">
                    Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>
</div> 