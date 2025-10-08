<?php
require_once __DIR__ . '/../http/response.php';
require_once __DIR__ . '/../DAO/cursosDAO.php';

class AlunosMiddleware
{
    public function stringJsonToStdClass($requestBody): stdClass
    {
        $stdAluno = json_decode($requestBody);

        if (json_last_error() !== JSON_ERROR_NONE) {
            (new Response(false, 'Aluno inválido', [
                'codigoError' => 'validation_error',
                'message' => 'Json inválido',
            ], null, 400))->send();
            exit();
        } else if (!isset($stdAluno->aluno)) {
            (new Response(false, 'Aluno inválido', [
                'codigoError' => 'validation_error',
                'message' => 'Não foi enviado o objeto aluno',
            ], null, 400))->send();
            exit();
        }
        return $stdAluno;
    }

    public function isValidNome(string $nome = null): self
    {
        if (!isset($nome) || strlen(trim($nome)) < 2) {
            (new Response(false, 'Nome inválido', [
                'codigoError' => 'validation_error',
                'message' => 'O nome deve ter pelo menos 2 caracteres',
            ], null, 400))->send();
            exit();
        }
        return $this;
    }

    public function isValidIdade($idade): self
    {
        if (!isset($idade) || !is_numeric($idade) || $idade < 15 || $idade > 99) {
            (new Response(false, 'Idade inválida', [
                'codigoError' => 'validation_error',
                'message' => 'A idade deve estar entre 15 e 99 anos',
            ], null, 400))->send();
            exit();
        }
        return $this;
    }

    public function isValidCursoId($curso_id): self
    {
        if (!isset($curso_id) || !is_numeric($curso_id) || $curso_id <= 0) {
            (new Response(false, 'Curso inválido', [
                'codigoError' => 'validation_error',
                'message' => 'O curso_id fornecido não é válido',
            ], null, 400))->send();
            exit();
        }

       
        $cursosDAO = new cursosDAO();
        $curso = $cursosDAO->readById((int)$curso_id);
        if (!$curso) {
            (new Response(false, 'Curso não existe', [
                'codigoError' => 'validation_error',
                'message' => 'O curso_id informado não existe',
            ], null, 400))->send();
            exit();
        }
        return $this;
    }

    private function normalizarNome(string $nome): string
    {
    
        $nome = trim(preg_replace('/\s+/', ' ', $nome));
        $nome = mb_convert_case($nome, MB_CASE_TITLE, "UTF-8");
        return $nome;
    }

    public function validarDadosCriacao(): stdClass
    {
        $requestBody = file_get_contents('php://input');
        $stdAluno = $this->stringJsonToStdClass($requestBody);

        $this->isValidNome($stdAluno->aluno->nome);
        $this->isValidIdade($stdAluno->aluno->idade);
        $this->isValidCursoId($stdAluno->aluno->cursos->curso_id);

        $stdAluno->aluno->nome = $this->normalizarNome($stdAluno->aluno->nome);

        return $stdAluno;
    }
}