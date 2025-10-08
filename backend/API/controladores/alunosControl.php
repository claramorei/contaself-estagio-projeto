<?php
require_once __DIR__ . '/../src/DAO/alunosDAO.php';
require_once __DIR__ . '/../src/models/alunos.php';
require_once __DIR__ . '/../src/models/cursos.php';
require_once __DIR__ . '/../src/DB/Database.php';
require_once __DIR__ . '/../http/Response.php';
require_once __DIR__ . '/../utils/logger.php';

class alunosControl
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
        $curso_id = $aluno->getcursos()->getId();
        if ($curso_id === null) {
            $curso_id = 1;
        }

        $query = 'INSERT INTO alunos (
                    nome,
                    idade,
                    curso_id
                ) VALUES (
                    :nome,
                    :idade,
                    :curso_id )';

        $statement = Database::getConnection()->prepare($query);

        $statement->bindValue(':nome', $aluno->getnome(), PDO::PARAM_STR);
        $statement->bindValue(':idade', $aluno->getidade(), PDO::PARAM_INT);
        $statement->bindValue(':curso_id', $curso_id, PDO::PARAM_INT);

        $statement->execute();

        $aluno->setid((int) Database::getConnection()->lastInsertId());

        return $aluno;
    }

    private function createWithId(alunos $aluno): alunos
    {
        $curso_id = $aluno->getcursos()->getId();
        if ($curso_id === null) {
            $curso_id = 1;
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
                    :curso_id )';

        $statement = Database::getConnection()->prepare($query);

        $statement->bindValue(':id', $aluno->getid(), PDO::PARAM_INT);
        $statement->bindValue(':nome', $aluno->getnome(), PDO::PARAM_STR);
        $statement->bindValue(':idade', $aluno->getidade(), PDO::PARAM_INT);
        $statement->bindValue(':curso_id', $curso_id, PDO::PARAM_INT);

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
        JOIN cursos ON alunos.curso_id = cursos.id
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
        JOIN cursos ON alunos.curso_id = cursos.id
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
        JOIN cursos ON alunos.curso_id = cursos.id
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

    public function readByCursoNome(string $cursoNome): array
    {
        $query = '
        SELECT 
            alunos.id,
            alunos.nome,
            alunos.idade,
            cursos.id AS curso_id,
            cursos.nome AS curso_nome
        FROM alunos
        JOIN cursos ON alunos.curso_id = cursos.id
        WHERE cursos.nome = :cursoNome
        ORDER BY alunos.nome ASC';

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':cursoNome', $cursoNome, PDO::PARAM_STR);
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
        $query = '
        SELECT 
            alunos.id,
            alunos.nome,
            alunos.idade,
            cursos.id AS curso_id,
            cursos.nome AS curso_nome
        FROM alunos
        JOIN cursos ON alunos.curso_id = cursos.id
        WHERE cursos.id = :cursoId
        ORDER BY alunos.nome ASC';

        $statement = Database::getConnection()->prepare($query);
        $statement->bindValue(':cursoId', $cursoId, PDO::PARAM_INT);
        $statement->execute();

        $resultados = [];
        while ($linha = $statement->fetch(PDO::FETCH_OBJ)) {
            $curso = new cursos($linha->curso_id, $linha->curso_nome);
            $aluno = new alunos($linha->id, $linha->nome, $linha->idade, $curso);
            $resultados[] = $aluno;
        }
        return $resultados;
    }

    public function update(alunos $aluno): bool
    {
        $query = 'UPDATE alunos 
                  SET nome = :nome,
                      idade = :idade,
                      curso_id = :curso_id
                  WHERE id = :id';

        $statement = Database::getConnection()->prepare($query);

        $statement->bindValue(':nome', $aluno->getnome(), PDO::PARAM_STR);
        $statement->bindValue(':idade', $aluno->getidade(), PDO::PARAM_INT);
        $statement->bindValue(':curso_id', $aluno->getcursos()->getId(), PDO::PARAM_INT);
        $statement->bindValue(':id', $aluno->getid(), PDO::PARAM_INT);

        $statement->execute();

        return $statement->rowCount() > 0;
    }

  
public function totalPorCurso(): void
{
    try {
        $alunosDAO = new alunosDAO();
        $total = $alunosDAO->totalAlunosPorCurso();

        (new Response(
            success: true,
            message: 'Total de alunos por curso obtido com sucesso',
            data: $total,
            httpCode: 200
        ))->send();
    } catch (Exception $e) {
        (new Response(
            success: false,
            message: $e->getMessage(),
            httpCode: 400
        ))->send();
    }
}

public function mediaIdadePorCurso(): void
{
    try {
        $alunosDAO = new alunosDAO();
        $media = $alunosDAO->mediaIdadePorCurso();

        (new Response(
            success: true,
            message: 'Média de idade por curso obtida com sucesso',
            data: $media,
            httpCode: 200
        ))->send();
    } catch (Exception $e) {
        (new Response(
            success: false,
            message: $e->getMessage(),
            httpCode: 400
        ))->send();
    }
}
    
   public function index(): void
{
    try {
        $alunosDAO = new alunosDAO();
        
        // Get filter parameters
        $cursoPorNome = $_GET['curso'] ?? null;
        $cursoPorId = isset($_GET['curso_id']) ? (int)$_GET['curso_id'] : null;
        
        // Apply filters
        if ($cursoPorNome) {
            $alunos = $alunosDAO->readByCursoNome($cursoPorNome);
        } elseif ($cursoPorId) {
            $alunos = $alunosDAO->readByCursoId($cursoPorId);
        } else {
            $alunos = $alunosDAO->readAll();
        }

        (new Response(
            success: true,
            message: 'Alunos listados com sucesso',
            data: ['alunos' => $alunos],
            httpCode: 200
        ))->send();
    } catch (Exception $e) {
        (new Response(
            success: false,
            message: 'Erro ao listar alunos: ' . $e->getMessage(),
            httpCode: 400
        ))->send();
    }
}

    public function show(int $id): void 
    {
        try {
            $aluno = $this->readById($id);
            
            if (!$aluno) {
                (new Response(
                    success: false,
                    message: 'Aluno não encontrado',
                    httpCode: 404
                ))->send();
                return;
            }

            $dados = [
                'id' => $aluno->getid(),
                'nome' => $aluno->getnome(),
                'idade' => $aluno->getidade(),
                'curso' => [
                    'id' => $aluno->getcursos()->getId(),
                ]
            ];

            (new Response(
                success: true,
                message: 'Aluno encontrado com sucesso',
                data: ['aluno' => $dados],
                httpCode: 200
            ))->send();
        } catch (Exception $e) {
            (new Response(
                success: false,
                message: 'Erro ao buscar aluno: ' . $e->getMessage(),
                httpCode: 500
            ))->send();
        }
    }

   public function store(array $data): void
{
    try {
        if (empty($data['nome'])) {
            throw new Exception('Nome do aluno é obrigatório');
        }

        if (empty($data['idade'])) {
            throw new Exception('Idade do aluno é obrigatória');
        }

        if (empty($data['cursos']) || empty($data['cursos']['id'])) {
            throw new Exception('Curso é obrigatório');
        }

        $curso = new cursos(
            id: $data['cursos']['id'],
            nome: $data['cursos']['nome'] ?? ''
        );

        $aluno = new alunos(
            id: null,
            nome: $data['nome'],
            idade: (int)$data['idade'],
            cursos: $curso
        );

        $alunosDAO = new alunosDAO();
        $aluno = $alunosDAO->create($aluno);

        (new Response(
            success: true,
            message: 'Aluno cadastrado com sucesso',
            data: ['aluno' => $aluno],
            httpCode: 200
        ))->send();
    } catch (Exception $e) {
        (new Response(
            success: false,
            message: $e->getMessage(),
            httpCode: 400
        ))->send();
    }
}
public function delete(int $id): void
{
  
    try {
        if ($id <= 0) {
            throw new Exception('ID inválido');
        }

        $alunosDAO = new alunosDAO();
        $success = $alunosDAO->delete($id);

        (new Response(
            success: true,
            message: 'Aluno excluído com sucesso',
            httpCode: 200
        ))->send();
    } catch (Exception $e) {
        (new Response(
            success: false,
            message: $e->getMessage(),
            httpCode: 400
        ))->send();
    }
}
}
