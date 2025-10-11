<?php
namespace App\Facade;

use Categoria;

interface CategoriaFacade
{
    public function crearCategoria(String $nombre, String $descripcion): bool;
    public function listarCategoria(): array;
    public function obtenerCategoria(int $id): ?Categoria;
    public function eliminarCategoria(int $id): bool;
    public function actualizarCategoria(int $id, String $nombre, String $descripcion): bool;
}
