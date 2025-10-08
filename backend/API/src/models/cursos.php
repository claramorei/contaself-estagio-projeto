<?php

declare(strict_types=1);

class cursos implements JsonSerializable
{
    public function __construct(
        private ?int $id = null,
        private string $nome = ''
    ) {
    }

    public function getId(): ?int 
    {
        return $this->id;
    }

    public function getIdcursos(): ?int  // Added for backward compatibility
    {
        return $this->id;
    }

    public function setIdcursos(?int $id): self  // Changed to match getId
    {
        $this->id = $id;
        return $this;
    }

    public function getNomecursos(): string 
    {
        return $this->nome;
    }

    public function getNome(): string  // Added for consistency
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome
        ];
    }
}