<?php

declare(strict_types=1);

require_once __DIR__ . '/cursos.php'; 

class alunos implements JsonSerializable
{
    public function __construct(
        private int $id,
        private string $nome,
        private int $idade,
        private cursos $cursos
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getid(),
            'nome' => $this->getnome(),
            'idade' => $this->getidade(),
            'cursos' => [
                'curso_id' => $this->cursos->getIdcursos()
            ]
        ];
    }

    public function getid(): int|null
    {
        return $this->id;
    }

    public function setid(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getnome(): string
    {
        return $this->nome;
    }

    public function setnome(string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }

    public function getidade(): int
    {
        return $this->idade;
    }

    public function setidade(int $idade): self
    {
        $this->idade = $idade;
        return $this;
    }

    public function getcursos(): cursos
    {
        return $this->cursos;
    }

    public function setcursos(cursos $cursos): self
    {
        $this->cursos = $cursos;
        return $this;
    }
}
