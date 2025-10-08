<?php

require_once __DIR__ . '/../models/alunos.php';
require_once __DIR__ . '/../models/cursos.php';
require_once __DIR__ . '/../DB/Database.php';

class alunosDAO
{
    public function create(alunos $aluno): alunos
    {
        $id = $aluno->getid();
        return isset($id) ? $this->createWithId($aluno) : $this->createWithoutId($aluno);
    }

    private function createWithoutId(alunos $aluno): alunos
    {
        $idcursos = $aluno->getcursos()->getId();
        if ($idcursos === null) {
            $idcursos = 1;
        }

        $query = 'INSERT INTO alunos (
                    nome,
                    idade,
                    curso_id
                ) VALUES (
                    :nome,
                    :idade,
                    :idcursos )';

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':nome', $aluno->getnome(), PDO::PARAM_STR);
        $statement->bindValue(':idade', $aluno->getidade(), PDO::PARAM_INT);
        $statement->bindValue(':idcursos', $idcursos, PDO::PARAM_INT);
        $statement->execute();

        $aluno->setid((int) Database::getConnection()->lastInsertId());
        return $aluno;
    }

    private function createWithId(alunos $aluno): alunos
    {
        $idcursos = $aluno->getcursos()->getId();
        if ($idcursos === null) {
            $idcursos = 1;
        }

        $query = 'INSERT INTO alunos (
                    id,
                    nome,
                    idade,
                    curso_id
                ) VALUES (
                    :id,
                    :nome,
                    :idade,
                    :idcursos )';

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':id', $aluno->getid(), PDO::PARAM_INT);
        $statement->bindValue(':nome', $aluno->getnome(), PDO::PARAM_STR);
        $statement->bindValue(':idade', $aluno->getidade(), PDO::PARAM_INT);
        $statement->bindValue(':idcursos', $idcursos, PDO::PARAM_INT);
        $statement->execute();

        return $aluno;
    }

  public function readAll(): array 
{
    $query = "
        SELECT 
            a.id,
            a.nome,
            a.idade,
            c.id as curso_id,
            c.nome as curso_nome
        FROM alunos a
        LEFT JOIN cursos c ON a.curso_id = c.id
        ORDER BY a.nome
    ";

    $statement = Database::getConnection()->query($query);
    $resultados = [];

    while ($linha = $statement->fetch(PDO::FETCH_ASSOC)) {
        $curso = new cursos($linha['curso_id'], $linha['curso_nome']);
        $aluno = new alunos($linha['id'], $linha['nome'], $linha['idade'], $curso);
        $resultados[] = $aluno;
    }

    return $resultados;
}

    public function readById(int $id): ?alunos
    {
        $query = "
            SELECT 
                a.id,
                a.nome,
                a.idade,
                c.id AS curso_id,
                c.nome AS curso_nome
            FROM alunos a
            LEFT JOIN cursos c ON a.curso_id = c.id
            WHERE a.id = :id
            LIMIT 1
        ";

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $linha = $statement->fetch(PDO::FETCH_OBJ);
        if (!$linha) {
            return null;
        }

        $curso = new cursos(
            id: $linha->curso_id ?? null,
            nome: $linha->curso_nome ?? ''
        );
        
        return new alunos(
            cursos: $curso,
            id: $linha->id,
            nome: $linha->nome,
            idade: $linha->idade
        );
    }

    public function readByNome(string $nome): ?alunos
    {
        $query = "
            SELECT 
                a.id,
                a.nome,
                a.idade,
                c.id AS curso_id,
                c.nome AS curso_nome
            FROM alunos a
            LEFT JOIN cursos c ON a.curso_id = c.id
            WHERE a.nome = :nome
            LIMIT 1
        ";

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':nome', $nome, PDO::PARAM_STR);
        $statement->execute();

        $linha = $statement->fetch(PDO::FETCH_OBJ);
        if (!$linha) {
            return null;
        }

        $curso = new cursos(
            id: $linha->curso_id ?? null,
            nome: $linha->curso_nome ?? ''
        );
        
        return new alunos(
            cursos: $curso,
            id: $linha->id,
            nome: $linha->nome,
            idade: $linha->idade
        );
    }

    public function update(alunos $aluno): bool
    {
        $query = 'UPDATE alunos 
                  SET nome = :nome,
                      idade = :idade,
                      curso_id = :idcursos
                  WHERE id = :id';

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':nome', $aluno->getnome(), PDO::PARAM_STR);
        $statement->bindValue(':idade', $aluno->getidade(), PDO::PARAM_INT);
        $statement->bindValue(':idcursos', $aluno->getcursos()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':id', $aluno->getid(), PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

 

    public function totalAlunosPorCurso(): array
    {
        $query = "
            SELECT 
                c.id AS curso_id, 
                c.nome AS curso_nome, 
                COUNT(a.id) AS total_alunos
            FROM cursos c
            LEFT JOIN alunos a ON a.curso_id = c.id
            GROUP BY c.id, c.nome
            ORDER BY c.nome ASC
        ";
        
        $statement = Database::getConnection()->query($query);
        $resultados = [];
        
        while ($linha = $statement->fetch(PDO::FETCH_OBJ)) {
            $resultados[] = [
                'curso_id' => $linha->curso_id,
                'curso_nome' => $linha->curso_nome,
                'total_alunos' => $linha->total_alunos
            ];
        }
        return $resultados;
    }

    public function mediaIdadePorCurso(): array
    {
        $query = "
            SELECT 
                c.id AS curso_id, 
                c.nome AS curso_nome, 
                AVG(a.idade) AS media_idade
            FROM cursos c
            LEFT JOIN alunos a ON a.curso_id = c.id
            GROUP BY c.id, c.nome
            ORDER BY c.nome ASC
        ";
        
        $statement = Database::getConnection()->query($query);
        $resultados = [];
        
        while ($linha = $statement->fetch(PDO::FETCH_OBJ)) {
            $resultados[] = [
                'curso_id' => $linha->curso_id,
                'curso_nome' => $linha->curso_nome,
                'media_idade' => $linha->media_idade ? round($linha->media_idade, 2) : null
            ];
        }
        return $resultados;
    }
   public function readByCursoNome(string $cursoNome): array
{
    $query = "
        SELECT 
            a.id,
            a.nome,
            a.idade,
            c.id AS curso_id,
            c.nome AS curso_nome
        FROM alunos a
        INNER JOIN cursos c ON a.curso_id = c.id
        WHERE LOWER(c.nome) LIKE LOWER(:curso_nome)
        ORDER BY a.nome ASC
    ";

    $statement = Database::getConnection()->prepare($query);
    $statement->bindValue(':curso_nome', "%{$cursoNome}%", PDO::PARAM_STR);
    $statement->execute();

    $resultados = [];
    while ($linha = $statement->fetch(PDO::FETCH_OBJ)) {
        $curso = new cursos($linha->curso_id, $linha->curso_nome);
        $aluno = new alunos($linha->id, $linha->nome, $linha->idade, $curso);
        $resultados[] = $aluno;
    }

    return $resultados;
}

public function readByCursoId(int $cursoId): array
{
    $query = "
        SELECT 
            a.id,
            a.nome,
            a.idade,
            c.id AS curso_id,
            c.nome AS curso_nome
        FROM alunos a
        INNER JOIN cursos c ON a.curso_id = c.id
        WHERE c.id = :curso_id
        ORDER BY a.nome ASC
    ";

    $statement = Database::getConnection()->prepare($query);
    $statement->bindValue(':curso_id', $cursoId, PDO::PARAM_INT);
    $statement->execute();

    $resultados = [];
    while ($linha = $statement->fetch(PDO::FETCH_OBJ)) {
        $curso = new cursos($linha->curso_id, $linha->curso_nome);
        $aluno = new alunos($linha->id, $linha->nome, $linha->idade, $curso);
        $resultados[] = $aluno;
    }

    return $resultados;
}
public function delete(int $id): bool
{
    $query = "DELETE FROM alunos WHERE id = :id";
    
    $statement = Database::getConnection()->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    return $statement->rowCount() > 0;
}
}