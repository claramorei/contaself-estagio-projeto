<?php

declare(strict_types=1);

require_once __DIR__ . '/cursos.php';

class alunos implements JsonSerializable
{
    public function __construct(
        private ?int $id = null,
        private string $nome = '',
        private int $idade = 0,
        private cursos $cursos = new cursos()
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getid(),
            'nome' => $this->getnome(),
            'idade' => $this->getidade(),
            'cursos' => [
                'curso_id' => $this->cursos->getId()
            ]
        ];
    }

    public function getid(): ?int
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

     public function setNome(string $nome): self
    {
        // Normalize name
        $nome = trim($nome);
        $nome = mb_strtolower($nome, 'UTF-8');
        $nome = preg_replace('/\s+/', ' ', $nome);
        $nome = mb_convert_case($nome, MB_CASE_TITLE, 'UTF-8');
        
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
