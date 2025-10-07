<?php

declare(strict_types=1);

class cursos implements JsonSerializable
{
    public function __construct(
        private int $id,
        private string $nome,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome
        ];
    }

    public function getIdcursos(): int|null
    {
        return $this->id;
    }

    public function setIdcursos(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getNomecursos(): string
    {
        return $this->nome;
    }

    public function setNomecursos(string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }
}
