<?php
namespace Repository\Impl;

use Categoria;
use Database;
use PDO;
use PDOException;
use Repository\CategoriaRepositoryInterface;

class CategoriaRepositoryImpl implements CategoriaRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConexion();
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->connection->query("SELECT * FROM categoria");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($r) => new Categoria(
                (int)$r['categoria_id'],
                $r['nombre'],
                $r['descripcion']
            ), $rows);

        } catch (PDOException $e) {
            error_log("Error en findAll: " . $e->getMessage());
            return [];
        }
    }

    public function findById(int $id): ?Categoria
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM categorias WHERE categoria_id = :id");
            $stmt->execute(['id' => $id]);
            $r = $stmt->fetch(PDO::FETCH_ASSOC);

            return $r ? new Categoria(
                (int)$r['categoria_id'],
                $r['nombre'],
                $r['descripcion']
            ) : null;
        } catch (PDOException $e) {
            error_log("Error en findById: " . $e->getMessage());
            return null;
        }
    }

    public function save(Categoria $categoria): bool
    {
        try {
            $stmt = $this->connection->prepare("
                INSERT INTO categoria (nombre, descripcion)
                VALUES (:nombre, :descripcion)
            ");
            return $stmt->execute([
                'nombre' => $categoria->getNombre(),
                'descripcion' => $categoria->getDescripcion()
            ]);
        } catch (PDOException $e) {
            error_log("Error en save: " . $e->getMessage());
            return false;
        }
    }

    public function update(Categoria $categoria): bool
    {
        try {
            $stmt = $this->connection->prepare("
                UPDATE categoria
                SET nombre = :nombre, descripcion = :descripcion
                WHERE categoria_id = :id
            ");
            return $stmt->execute([
                'nombre' => $categoria->getNombre(),
                'descripcion' => $categoria->getDescripcion(),
                'id' => $categoria->getId()
            ]);
        } catch (PDOException $e) {
            error_log("Error en update: " . $e->getMessage());
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->connection->prepare("UPDATE categoria SET habilitado = false WHERE categoria_id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            error_log("Error en delete: " . $e->getMessage());
            return false;
        }
    }
}
