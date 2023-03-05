<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
	$uuid = Uuid::uuid4()->toString();
	$response->getBody()->write("Hello world! UUID: " . $uuid);
	return $response;
});

$app->run();
