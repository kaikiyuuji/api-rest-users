<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';


$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();


$app->addBodyParsingMiddleware();


$dataFile = __DIR__ . '/../data/users.json';

// Função para ler os dados
function readUsers($dataFile) {
    if (!file_exists($dataFile)) {
        return [];
    }
    $content = file_get_contents($dataFile);
    return json_decode($content, true) ?? [];
}

// Função para salvar os dados
function saveUsers($dataFile, $users) {
    if (!is_dir(dirname($dataFile))) {
        mkdir(dirname($dataFile), 0777, true);
    }
    file_put_contents($dataFile, json_encode($users));
}

// Rota para criar usuário
$app->post('/users', function (Request $request, Response $response) use ($dataFile) {
    $data = $request->getParsedBody();
    $users = readUsers($dataFile);
    
    // Validação
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        $response->getBody()->write(json_encode(['error' => 'Dados incompletos']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
    
    // Verifica se email já existe
    foreach ($users as $user) {
        if ($user['email'] === $data['email']) {
            $response->getBody()->write(json_encode(['error' => 'Email já cadastrado']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    }
    
    // Cria novo usuário
    $newUser = [
        'id' => count($users) + 1,
        'username' => $data['username'],
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_DEFAULT)
    ];
    
    $users[] = $newUser;
    saveUsers($dataFile, $users);
    
    $response->getBody()->write(json_encode($newUser));
    return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
});

// lista todos os usuários
$app->get('/users', function (Request $request, Response $response) use ($dataFile) {
    $users = readUsers($dataFile);
    $response->getBody()->write(json_encode($users));
    return $response->withHeader('Content-Type', 'application/json');
});

// busca usuário por ID
$app->get('/users/{id}', function (Request $request, Response $response, array $args) use ($dataFile) {
    $id = $args['id'];
    $users = readUsers($dataFile);
    
    foreach ($users as $user) {
        if ($user['id'] == $id) {
            $response->getBody()->write(json_encode($user));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    
    $response->getBody()->write(json_encode(['error' => 'Usuário não encontrado']));
    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
});

// atualiza usuário
$app->put('/users/{id}', function (Request $request, Response $response, array $args) use ($dataFile) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    $users = readUsers($dataFile);
    
    foreach ($users as &$user) {
        if ($user['id'] == $id) {
            if (isset($data['username'])) $user['username'] = $data['username'];
            if (isset($data['email'])) $user['email'] = $data['email'];
            if (isset($data['password'])) $user['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            
            saveUsers($dataFile, $users);
            
            $response->getBody()->write(json_encode($user));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    
    $response->getBody()->write(json_encode(['error' => 'Usuário não encontrado']));
    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
});

// deleta usuário
$app->delete('/users/{id}', function (Request $request, Response $response, array $args) use ($dataFile) {
    $id = $args['id'];
    $users = readUsers($dataFile);
    
    foreach ($users as $key => $user) {
        if ($user['id'] == $id) {
            unset($users[$key]);
            $users = array_values($users); // Reindexar array
            
            saveUsers($dataFile, $users);
            
            $response->getBody()->write(json_encode(['message' => 'Usuário deletado com sucesso']));
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    
    $response->getBody()->write(json_encode(['error' => 'Usuário não encontrado']));
    return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
});

$app->run(); 