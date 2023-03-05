<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/temporary-storage.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
	$temporary_storage = new TemporaryStorage();
	$response->getBody()->write("Hello world! UUID: " . $temporary_storage->get_directory());
	return $response;
});

$app->run();
