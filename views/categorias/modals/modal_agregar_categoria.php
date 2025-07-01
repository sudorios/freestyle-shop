<div id="modal_agregar_categoria" class="fixed inset-0 hidden overflow-y-auto h-full w-full z-30">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Agregar Categoría</h3>
            <form id="formAgregarCategoria" action="views/categorias/categoria_registrar.php" method="POST">
                <div class="mb-4">
                    <label for="txtnombre" class="block text-sm font-medium text-gray-700">Nombre de la Categoría</label>
                    <input type="text" id="txtnombre" name="txtnombre" required
                        class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50">
                </div>

                <div class="mb-4">
                    <label for="txtdescripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="txtdescripcion" name="txtdescripcion" rows="4" required
                        class="mt-1 block w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition duration-150 ease-in-out shadow-sm px-3 py-2 bg-gray-50"></textarea>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" onclick="cerrarModalAgregarCategoria()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Cancelar</button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div> 