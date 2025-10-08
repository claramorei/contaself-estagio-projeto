<?php
require_once __DIR__ . '/../src/models/cursos.php';
require_once __DIR__ . '/../src/DAO/cursosDAO.php';
require_once __DIR__ . '/../http/Response.php';
require_once __DIR__ . '/../utils/logger.php';

class CursosControl
{
    public function index(): never
    {
        $cursosDAO = new cursosDAO();
        $cursos = $cursosDAO->readAll();

        (new Response(
            success: true,
            message: 'Cursos selecionados com sucesso',
            data: ['cursos' => $cursos],
            httpCode: 200
        ))->send();
        exit();
    }

    public function show(int $idcurso): never
    {
        $cursosDAO = new cursosDAO();
        $curso = $cursosDAO->readById($idcurso);

        if (isset($curso)) {
            (new Response(
                success: true,
                message: 'Curso encontrado com sucesso',
                data: ['curso' => $curso],
                httpCode: 200
            ))->send();
        } else {
            (new Response(
                success: false,
                message: 'Curso não encontrado',
                httpCode: 404
            ))->send();
        }
        exit();
    }

  public function store(array $data): void
{
    try {
        if (empty($data['nome'])) {
            throw new Exception('Nome do curso é obrigatório');
        }

        $curso = new cursos(
            nome: trim($data['nome'])
        );

        $cursosDAO = new cursosDAO();
        $curso = $cursosDAO->create($curso);

        (new Response(
            success: true,
            message: 'Curso criado com sucesso',
            data: ['curso' => $curso],
            httpCode: 201
        ))->send();
    } catch (Exception $e) {
        (new Response(
            success: false,
            message: $e->getMessage(),
            httpCode: 400
        ))->send();
    }
}

    public function edit(stdClass $stdCurso): never
    {
        $curso = new cursos(
            $stdCurso->curso->id,
            $stdCurso->curso->nome
        );

        $cursosDAO = new cursosDAO();

        if ($cursosDAO->update($curso)) {
            (new Response(
                success: true,
                message: "Atualizado com sucesso",
                data: ['curso' => $curso],
                httpCode: 200
            ))->send();
        } else {
            (new Response(
                success: false,
                message: "Não foi possível atualizar o curso.",
                data: [
                    'codigoError' => 'validation_error',
                    'message' => 'Não é possível atualizar para um curso que já existe',
                ],
                httpCode: 400
            ))->send();
        }
        exit();
    }

    public function destroy(int $idcurso): never
    {
        $cursosDAO = new cursosDAO();

        if ($cursosDAO->delete($idcurso)) {
            (new Response(httpCode: 204))->send();
        } else {
            (new Response(
                success: false,
                message: 'Não foi possível excluir o curso',
                data: [
                    'cod' => 'delete_error',
                    'message' => 'O curso não pode ser excluído'
                ],
                httpCode: 400
            ))->send();
        }
        exit();
    }
}
?>