<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/temporary-storage.php';

$app = AppFactory::create();

$app->post('/', function (Request $request, Response $response, $args) {
	$uploadedFiles = $request->getUploadedFiles();

	if (empty($uploadedFiles['file'])) {
		throw new \Exception('Nenhum plugin ou tema WordPress foi enviado');
	}

	$file = $uploadedFiles['file'];

	if ($file->getSize() > 50 * 1024 * 1024) {
		throw new \Exception('O tamanho máximo do arquivo é de 50MB');
	}

	if ($file->getClientMediaType() !== 'application/zip') {
		throw new \Exception('Somente arquivos ZIP são permitidos');
	}

	$temporary_storage = new TemporaryStorage();
	$directory = $temporary_storage->get_directory();

	mkdir($directory, 0777, true);
	$filename = 'file.zip';
	$file->moveTo($directory . '/' . $filename);

	$response->getBody()->write("Hello world! UUID: {$temporary_storage->get_directory()}");
	return $response;
});

$app->run();
