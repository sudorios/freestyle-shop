<?php

function getAllProductsQuery()
{
    return "SELECT p.*, s.nombre_subcategoria FROM producto p LEFT JOIN subcategoria s ON p.id_subcategoria = s.id_subcategoria WHERE p.estado = true ORDER BY p.id_producto DESC";
}

function getProductByIdQuery()
{
    return "SELECT * FROM producto WHERE id_producto = $1";
}

function insertProductQuery()
{
    return "INSERT INTO producto (ref_producto, nombre_producto, descripcion_producto, id_subcategoria, talla_producto, estado) VALUES ($1, $2, $3, $4, $5, $6)";
}

function updateProductQuery()
{
    return "UPDATE producto SET ref_producto = $1, nombre_producto = $2, descripcion_producto = $3, id_subcategoria = $4, talla_producto = $5, actualizado_en = CURRENT_TIMESTAMP WHERE id_producto = $6";
}

function deleteProductQuery()
{
    return "UPDATE producto SET estado = false WHERE id_producto = $1";
}

function getProductByRefQuery()
{
    return "SELECT * FROM producto WHERE ref_producto = $1";
}

function updateProductByRefQuery()
{
    return "UPDATE producto SET nombre_producto = $1, descripcion_producto = $2, id_subcategoria = $3, talla_producto = $4, actualizado_en = CURRENT_TIMESTAMP WHERE ref_producto = $5";
}

function getAllSubcategoriasQuery()
{
    return "SELECT id_subcategoria, nombre_subcategoria FROM subcategoria WHERE estado = true ORDER BY nombre_subcategoria ASC";
}
