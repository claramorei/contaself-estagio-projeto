<?php
// ========================
// CONFIGURAÇÕES INICIAIS
// ========================
ini_set('display_errors', 0); // não mostrar erros HTML na tela
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

// ========================
// LOG BÁSICO DE DEBUG
// ========================
$logFile = __DIR__ . '/debug.log';
file_put_contents($logFile, "=== Nova Requisição ===\n", FILE_APPEND);
file_put_contents($logFile, "URI: " . $_SERVER['REQUEST_URI'] . "\n", FILE_APPEND);
file_put_contents($logFile, "Method: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
file_put_contents($logFile, "Script: " . $_SERVER['SCRIPT_FILENAME'] . "\n\n", FILE_APPEND);

// ========================
// IMPORTA CONTROLADORES
// ========================
require_once __DIR__ . '/controladores/alunosControl.php';
require_once __DIR__ . '/controladores/cursosControl.php';
require_once __DIR__ . '/http/response.php';

// ========================
// PROCESSA REQUISIÇÃO
// ========================
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Base do projeto — AJUSTE se necessário
$basePath = '/contaself-estagio-projeto/backend/API';

// Limpa a rota
$request = str_replace($basePath, '', $request);
$request = strtok($request, '?');
$request = rtrim($request, '/');

// Função para garantir resposta sempre em JSON
function send_json($data, int $status = 200) {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

try {
    switch ($request) {

        // --------------------
        // ROTA: /cursos
        // --------------------


        case '/cursos':
            $controller = new cursosControl();

            if ($method === 'GET') {
                $controller->index();
            } elseif ($method === 'POST') {
                $rawData = file_get_contents('php://input');
                file_put_contents($logFile, "RAW DATA: " . $rawData . "\n", FILE_APPEND);

                $data = json_decode($rawData, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('JSON inválido: ' . json_last_error_msg());
                }

                if (!isset($data['curso']) || !isset($data['curso']['nome']) || trim($data['curso']['nome']) === '') {
                    throw new Exception('Campo "curso.nome" não encontrado ou está vazio no corpo da requisição.');
                }
                
                // Envia array com o nome do curso
                $controller->store($data['curso']);
            } else {
                send_json(['success' => false, 'message' => 'Método não permitido'], 405);
            }
            break;

        // --------------------
        // ROTA: /alunos
        // --------------------
         case (preg_match('/^\/alunos\/(\d+)$/', $request, $matches) ? true : false):
        $controller = new alunosControl();
        
        if ($method === 'DELETE') {
            $id = (int)$matches[1];
            $controller->delete($id);
        } else {
            send_json(['success' => false, 'message' => 'Método não permitido'], 405);
        }
        break;



    case '/alunos':
        $controller = new alunosControl();

        if ($method === 'GET') {
            $controller->index();
        } elseif ($method === 'POST') {
            $rawData = file_get_contents('php://input');
            file_put_contents($logFile, "RAW DATA: " . $rawData . "\n", FILE_APPEND);

            $data = json_decode($rawData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON inválido: ' . json_last_error_msg());
            }

            if (!isset($data['aluno'])) {
                throw new Exception('Campo "aluno" não encontrado no corpo da requisição.');
            }

            // ✅ Envia array diretamente (compatível com store(array))
            $controller->store($data['aluno']);
        } else {
            send_json(['success' => false, 'message' => 'Método não permitido'], 405);
        }
        break;


             case '/alunos/total-por-curso':
        $controller = new alunosControl();
        if ($method === 'GET') {
            $controller->totalPorCurso();
        } else {
            send_json(['success' => false, 'message' => 'Método não permitido'], 405);
        }
        break;

    case '/alunos/media-idade-por-curso':
        $controller = new alunosControl();
        if ($method === 'GET') {
            $controller->mediaIdadePorCurso();
        } else {
            send_json(['success' => false, 'message' => 'Método não permitido'], 405);
        }
        break;

        // --------------------
        // ROTA NÃO ENCONTRADA
        // --------------------
        default:
            send_json([
                'success' => false,
                'message' => 'Rota não encontrada: ' . $request,
                'request' => $request,
                'method' => $method
            ], 404);
    }
} catch (Throwable $e) {
    // Log completo no arquivo
    file_put_contents($logFile, "ERRO: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n", FILE_APPEND);

    // Envia JSON de erro
    send_json([
        'success' => false,
        'message' => $e->getMessage(),
        'file' => basename($e->getFile()),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ], 500);
}
