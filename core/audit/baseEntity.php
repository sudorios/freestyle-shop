<?php
abstract class BaseEntity
{
    protected ?bool $habilitado;
    protected ?DateTime $creado;
    protected ?DateTime $modificado;
    protected ?string $creado_por;
    protected ?string $modificado_por;

    public function __construct()
    {
        $this->creado = new DateTime();
        $this->modificado = new DateTime();
        $this->creado_por = null;
        $this->modificado_por = null;
        $this->habilitado = true;
    }

    public function getCreado(): ?DateTime
    {
        return $this->creado;
    }

    public function getModificado(): ?DateTime
    {
        return $this->modificado;
    }

    public function getCreadoPor(): ?string
    {
        return $this->creado_por;
    }

    public function getModificadoPor(): ?string
    {
        return $this->modificado_por;
    }

    public function getHabilitado(): ?bool
    {
        return $this->habilitado;
    }

    public function setCreado(DateTime $creado): void
    {
        $this->creado = $creado;
    }

    public function setModificado(DateTime $modificado): void
    {
        $this->modificado = $modificado;
    }

    public function setCreadoPor(string $creado_por): void
    {
        $this->creado_por = $creado_por;
    }

    public function setModificadoPor(string $modificado_por): void
    {
        $this->modificado_por = $modificado_por;
    }

    public function setHabilitado(bool $habilitado): void
    {
        $this->habilitado = $habilitado;
    }
}
