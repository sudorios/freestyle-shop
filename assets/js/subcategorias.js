document.addEventListener('DOMContentLoaded', function() {
    $('#modalEditarSubcategoria').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        // Obtener datos de la subcategoría vía AJAX
        $.ajax({
            url: 'subcategoria_edit.php',
            type: 'GET',
            data: { id: id },
            dataType: 'json',
            success: function(data) {
                $('#edit_id_subcategoria').val(data.id_subcategoria);
                $('#edit_nombre_subcategoria').val(data.nombre_subcategoria);
                $('#edit_descripcion_subcategoria').val(data.descripcion_subcategoria);
                // Cargar categorías
                $('#edit_id_categoria').empty();
                data.categorias.forEach(function(cat) {
                    var selected = cat.id_categoria == data.id_categoria ? 'selected' : '';
                    $('#edit_id_categoria').append('<option value="' + cat.id_categoria + '" ' + selected + '>' + cat.nombre_categoria + '</option>');
                });
            }
        });
    });

    // Manejar envío del formulario de edición
    $('#formEditarSubcategoria').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'subcategoria_registrar.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function() {
                location.reload();
            }
        });
    });
}); 