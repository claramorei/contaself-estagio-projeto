<?php
require_once __DIR__ . '/../http/response.php';
require_once __DIR__ . '/../DAO/cursosDAO.php';

class CursosMiddleware
{
    public function stringJsonToStdClass($requestBody): stdClass
    {
        $stdCurso = json_decode($requestBody);

        if (json_last_error() !== JSON_ERROR_NONE) {
            (new Response(false, 'Curso inválido', [
                'message' => 'Json inválido',
            ], null, 400))->send();
            exit();
        } else if (!isset($stdCurso->curso)) {
            (new Response(false, 'Curso inválido', [
                'message' => 'Não foi enviado o objeto curso',
            ], null, 400))->send();
            exit();
        }
        return $stdCurso;
    }

    public function isValidNome(string $nome = null): self
    {
        if (!isset($nome) || strlen(trim($nome)) < 2) {
            (new Response(false, 'Nome inválido', [
                'message' => 'O nome do curso deve ter pelo menos 2 caracteres',
            ], null, 400))->send();
            exit();
        }
        return $this;
    }

    public function isNomeDuplicado(string $nome): self
    {
        $cursosDAO = new cursosDAO();
        $todosCursos = $cursosDAO->readAll();
        foreach ($todosCursos as $curso) {
            if (mb_strtolower(trim($curso->getNomecursos())) === mb_strtolower(trim($nome))) {
                (new Response(false, 'Curso duplicado', [
                    'message' => 'Já existe um curso com esse nome',
                ], null, 400))->send();
                exit();
            }
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
        $stdCurso = $this->stringJsonToStdClass($requestBody);

        $this->isValidNome($stdCurso->curso->nome);
        $this->isNomeDuplicado($stdCurso->curso->nome);

        $stdCurso->curso->nome = $this->normalizarNome($stdCurso->curso->nome);

        return $stdCurso;
    }
}