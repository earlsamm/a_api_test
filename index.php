<?php

declare(strict_types=1);

require './vendor/autoload.php';

set_error_handler('ErrorHandler::handleError');
set_exception_handler('ErrorHandler::handleException');

$dotenv = Dotenv\Dotenv::createImmutable(dirname('./'));
$dotenv->load();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$parts = explode('/', $path);

$resource = $parts[2];

$id = $parts[3] ?? null;

if ($resource != 'tasks') {
    http_response_code(404);
    // header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
    exit;
}

header('Content-Type: application/json; charset= UTF-8');

// require "./src/TaskController.php";

$database = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);

$task_gateway = new TaskGateway($database);

$controller = new TaskController($task_gateway);

$controller->processRequest($_SERVER['REQUEST_METHOD'], $id);

//Now we add another route
$routes = 'sammy';
