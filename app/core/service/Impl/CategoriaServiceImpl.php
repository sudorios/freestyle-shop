<?php
namespace service\Impl;

use Categoria;
use Repository\Impl\CategoriaRepositoryImpl;
use Service\CategoriaService;

class CategoriaServiceImpl implements CategoriaService
{
    private CategoriaRepositoryImpl $repository;

    public function __construct()
    {
        $this->repository = new CategoriaRepositoryImpl();
    }

    public function listarCategoria(): array
    {
        return $this->repository->findAll();
    }

    public function obtenerCategoria(int $id): ?Categoria
    {
        return $this->repository->findById($id);
    }

    public function crearCategoria(string $nombre, string $descripcion): bool
    {
        if (empty($nombre)) {
            throw new \InvalidArgumentException("El nombre no puede estar vacío");
        }

        $categoria = new Categoria(null, $nombre, $descripcion);
        return $this->repository->save($categoria);
    }

    public function actualizarCategoria(int $id, string $nombre, string $descripcion): bool
    {
        $categoria = $this->repository->findById($id);
        if (!$categoria) {
            return false;
        }

        $categoria->setNombre($nombre);
        $categoria->setDescripcion($descripcion);

        return $this->repository->update($categoria);
    }

    public function eliminarCategoria(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
