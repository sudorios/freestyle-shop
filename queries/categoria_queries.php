<?php

function getAllCategorias() {
    return "SELECT * FROM categoria ORDER BY id_categoria ASC";
}

function getCategoriaByNombre() {
    return "SELECT id_categoria FROM categoria WHERE nombre_categoria = $1";
}

function insertCategoria() {
    return "INSERT INTO categoria(nombre_categoria, descripcion_categoria, estado_categoria, creado_en) VALUES ($1, $2, $3, $4)";
}

function updateCategoria() {
    return "UPDATE categoria SET nombre_categoria = $1, descripcion_categoria = $2, estado_categoria = $3 WHERE id_categoria = $4";
} 