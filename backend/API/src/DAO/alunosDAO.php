<?php
require_once __DIR__ . '/../models/alunos.php';
require_once __DIR__ . '/../models/cursos.php';
require_once __DIR__ . '/../db/Database.php';

class alunosDAO
{
    public function create(alunos $aluno): alunos
    {
        $id = $aluno->getid();
        if (isset($id)) {
            return $this->createWithId($aluno);
        } else {
            return $this->createWithoutId($aluno);
        }
    }

    private function createWithoutId(alunos $aluno): alunos
    {
        $idcursos = $aluno->getcursos()->getIdcursos();
        if ($idcursos === null) {
            $idcursos = 1;
        }

        $query = 'INSERT INTO alunos (
                    nome,
                    idade,
                    cursos_idcursos
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
        $idcursos = $aluno->getcursos()->getIdcursos();
        if ($idcursos === null) {
            $idcursos = 1;
        }

        $query = 'INSERT INTO alunos (
                    id,
                    nome,
                    idade,
                    cursos_idcursos
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
        $query = '
        SELECT 
            alunos.id,
            alunos.nome,
            alunos.idade,
            cursos.id AS curso_id,
            cursos.nome AS curso_nome
        FROM alunos
        JOIN cursos ON alunos.cursos_idcursos = cursos.id
        ORDER BY alunos.nome ASC';

        $statement = Database::getConnection()->query($query);

        $resultados = [];

        while ($linha = $statement->fetch(PDO::FETCH_OBJ)) {
            $curso = new cursos($linha->curso_id, $linha->curso_nome);
            $aluno = new alunos($linha->id, $linha->nome, $linha->idade, $curso);
            $resultados[] = $aluno;
        }

        return $resultados;
    }

    public function readById(int $id): ?alunos
    {
        $query = '
        SELECT 
            alunos.id,
            alunos.nome,
            alunos.idade,
            cursos.id AS curso_id,
            cursos.nome AS curso_nome
        FROM alunos
        JOIN cursos ON alunos.cursos_idcursos = cursos.id
        WHERE alunos.id = :id
        LIMIT 1';

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $linha = $statement->fetch(PDO::FETCH_OBJ);
        if (!$linha) {
            return null;
        }

        $curso = new cursos($linha->curso_id, $linha->curso_nome);
        return new alunos($linha->id, $linha->nome, $linha->idade, $curso);
    }

    public function readByNome(string $nome): ?alunos
    {
        $query = '
        SELECT 
            alunos.id,
            alunos.nome,
            alunos.idade,
            cursos.id AS curso_id,
            cursos.nome AS curso_nome
        FROM alunos
        JOIN cursos ON alunos.cursos_idcursos = cursos.id
        WHERE alunos.nome = :nome
        LIMIT 1';

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':nome', $nome, PDO::PARAM_STR);
        $statement->execute();

        $linha = $statement->fetch(PDO::FETCH_OBJ);
        if (!$linha) {
            return null;
        }

        $curso = new cursos($linha->curso_id, $linha->curso_nome);
        return new alunos($linha->id, $linha->nome, $linha->idade, $curso);
    }

    public function update(alunos $aluno): bool
    {
        $query = 'UPDATE alunos 
                  SET nome = :nome,
                      idade = :idade,
                      cursos_idcursos = :idcursos
                  WHERE id = :id';

        $statement = Database::getConnection()->prepare($query);

        $statement->bindValue(':nome', $aluno->getnome(), PDO::PARAM_STR);
        $statement->bindValue(':idade', $aluno->getidade(), PDO::PARAM_INT);
        $statement->bindValue(':idcursos', $aluno->getcursos()->getIdcursos(), PDO::PARAM_INT);
        $statement->bindValue(':id', $aluno->getid(), PDO::PARAM_INT);

        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $query = 'DELETE FROM alunos WHERE id = :id';
        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }
}