<?php
class Categoria extends BaseEntity
{
    private int $categoria_id;
    private String $nombre;
    private String $descripcion;

    public function __construct($categoria_id, $nombre, $descripcion)
    {
        parent::__construct();
        $this->categoria_id = $categoria_id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public function getId()
    {
        return $this->categoria_id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setId($categoria_id)
    {
        $this->categoria_id = $categoria_id;
    }

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
}
