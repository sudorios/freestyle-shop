<!-- Modal Editar Subcategoría -->
<div class="modal fade" id="modalEditarSubcategoria" tabindex="-1" role="dialog" aria-labelledby="modalEditarSubcategoriaLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarSubcategoriaLabel">Editar Subcategoría</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEditarSubcategoria">
          <input type="hidden" id="edit_id_subcategoria" name="id_subcategoria">
          <div class="form-group">
            <label for="edit_nombre_subcategoria">Nombre</label>
            <input type="text" class="form-control" id="edit_nombre_subcategoria" name="nombre_subcategoria" required>
          </div>
          <div class="form-group">
            <label for="edit_descripcion_subcategoria">Descripción</label>
            <textarea class="form-control" id="edit_descripcion_subcategoria" name="descripcion_subcategoria"></textarea>
          </div>
          <div class="form-group">
            <label for="edit_id_categoria">Categoría</label>
            <select class="form-control" id="edit_id_categoria" name="id_categoria" required>
              <!-- Opciones cargadas por JS -->
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
      </div>
    </div>
  </div>
</div> 