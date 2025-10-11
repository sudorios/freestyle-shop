<?php
namespace Service;

use Categoria;

interface CategoriaService
{
    public function listarCategoria(): array;
    public function obtenerCategoria(int $id): ?Categoria;
    public function crearCategoria(string $nombre, string $descripcion): bool;
    public function actualizarCategoria(int $id, string $nombre, string $descripcion): bool;
    public function eliminarCategoria(int $id): bool;
}
