<?php

function getAllCategoriasQuery()
{
    return "SELECT * FROM categoria WHERE estado_categoria = true ORDER BY id_categoria DESC";
}

function getCategoriaByIdQuery()
{
    return "SELECT id_categoria FROM categoria WHERE id_categoria = $1";
}

function getCategoriaByNombreQuery()
{
    return "SELECT id_categoria FROM categoria WHERE nombre_categoria = $1";
}

function getCategoriaByNombreExcludeIdQuery()
{
    return "SELECT id_categoria FROM categoria WHERE nombre_categoria = $1 AND id_categoria != $2";
}

function insertCategoriaQuery()
{
    return "INSERT INTO categoria(nombre_categoria, descripcion_categoria, estado_categoria, creado_en) VALUES ($1, $2, $3, $4)";
}

function updateCategoriaQuery()
{
    return "UPDATE categoria SET nombre_categoria = $1, descripcion_categoria = $2, estado_categoria = $3 WHERE id_categoria = $4";
}

function deleteCategoriaQuery()
{
    return "UPDATE categoria SET estado_categoria = false WHERE id_categoria = $1";
}

function getCategoriasActivasQuery()
{
    return "SELECT * FROM categoria WHERE estado_categoria = true ORDER BY nombre_categoria ASC";
}

function updateEstadoCategoriaQuery()
{
    return "UPDATE categoria SET estado_categoria = $1 WHERE id_categoria = $2";
}

function query_catalogo_por_categoria() {
    return "SELECT 
        cp.id AS id_catalogo,
        p.nombre_producto,
        p.descripcion_producto,
        ip.url_imagen,
        i.precio_venta,
        cp.oferta,
        c.nombre_categoria
    FROM 
        catalogo_productos cp
    JOIN producto p ON cp.producto_id = p.id_producto
    JOIN ingreso i ON cp.ingreso_id = i.id
    LEFT JOIN imagenes_producto ip ON cp.imagen_id = ip.id
    JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria
    JOIN categoria c ON s.id_categoria = c.id_categoria
    WHERE 
        cp.sucursal_id = 7
        AND (cp.estado = true OR cp.estado = 't')
        AND c.id_categoria = $1
    ORDER BY p.nombre_producto ASC";
}
?> 