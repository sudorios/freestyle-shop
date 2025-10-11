<?php
namespace App\Facade\Impl;

use App\Facade\CategoriaFacade;
use Categoria;
use Service\CategoriaService;

class CategoriaFacadeImpl implements CategoriaFacade
{
    private CategoriaService $categoriaService;

    public function __construct(CategoriaService $categoriaService)
    {
        $this->categoriaService = $categoriaService;
    }

    public function crearCategoria(String $nombre, String $descripcion): bool
    {
        return $this->categoriaService->crearCategoria($nombre, $descripcion);
    }

    public function listarCategoria(): array
    {
        return $this->categoriaService->listarCategoria();
    }

    public function obtenerCategoria(int $id): ?Categoria
    {
        return $this->categoriaService->obtenerCategoria($id);
    }

    public function eliminarCategoria(int $id): bool
    {
        return $this->categoriaService->eliminarCategoria($id);
    }

    public function actualizarCategoria(int $id, String $nombre, String $descripcion): bool
    {
        return $this->categoriaService->actualizarCategoria($id, $nombre, $descripcion);
    }
}
