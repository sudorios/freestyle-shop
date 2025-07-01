<div id="modalConfirmarBackground" class="fixed inset-0 bg-black opacity-75 hidden z-40"></div>
<div id="modalConfirmar" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto h-full w-full hidden">
  <div class="relative mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
    <h3 id="modalConfirmarMensaje" class="text-lg font-bold mb-4 text-center">¿Seguro que deseas continuar?</h3>
    <form id="formConfirmar" method="POST">
      <input type="hidden" name="id" id="modalConfirmarId">
      <div class="flex justify-end gap-2 mt-4">
        <button type="button" onclick="cerrarModalConfirmar()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">Cancelar</button>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded cursor-pointer">Confirmar</button>
      </div>
    </form>
  </div>
</div> 