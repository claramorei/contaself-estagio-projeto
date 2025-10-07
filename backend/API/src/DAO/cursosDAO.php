    
<?php
require_once __DIR__ . '/../models/cursos.php';
require_once __DIR__ . '/../db/Database.php';

class cursosDAO
{
    public function create(cursos $curso): cursos
    {
        $query = 'INSERT INTO cursos (nome) VALUES (:nome)';
        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':nome', $curso->getNomecursos(), PDO::PARAM_STR);
        $statement->execute();

        $curso->setIdcursos((int) Database::getConnection()->lastInsertId());
        return $curso;
    }

    public function readAll(): array
    {
        $query = 'SELECT id, nome FROM cursos ORDER BY nome ASC';
        $statement = Database::getConnection()->query($query);

        $resultados = [];
        while ($linha = $statement->fetch(PDO::FETCH_OBJ)) {
            $curso = new cursos($linha->id, $linha->nome);
            $resultados[] = $curso;
        }
        return $resultados;
    }

    public function readById(int $id): ?cursos
    {
        $query = 'SELECT id, nome FROM cursos WHERE id = :id LIMIT 1';
        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $linha = $statement->fetch(PDO::FETCH_OBJ);
        if (!$linha) {
            return null;
        }
        return new cursos($linha->id, $linha->nome);
    }

    public function update(cursos $curso): bool
    {
        $query = 'UPDATE cursos SET nome = :nome WHERE id = :id';
        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':nome', $curso->getNomecursos(), PDO::PARAM_STR);
        $statement->bindValue(':id', $curso->getIdcursos(), PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $query = 'DELETE FROM cursos WHERE id = :id';
        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }
}