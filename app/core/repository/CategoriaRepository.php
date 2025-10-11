<?php
namespace Repository;

use Categoria;

interface CategoriaRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Categoria;
    public function save(Categoria $categoria): bool;
    public function update(Categoria $categoria): bool;
    public function delete(int $id): bool;
}
