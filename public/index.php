<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/temporary-storage.php';

$app = AppFactory::create();

$app->post('/', function (Request $request, Response $response, $args) {
	// Check uploaded files
	$uploadedFiles = $request->getUploadedFiles();

	if (empty($uploadedFiles['file'])) {
		throw new \Exception('Nenhum plugin ou tema WordPress foi enviado');
	}

	$file = $uploadedFiles['file'];
	print_r($file->getError());

	if ($file->getSize() > 50 * 1024 * 1024) {
		throw new \Exception('O tamanho máximo do arquivo é de 50MB');
	}

	// if ($file->getClientMediaType() !== 'application/zip') {
	// 	print_r($file->getClientMediaType());
	// 	throw new \Exception('Somente arquivos ZIP são permitidos');
	// }

	print_r($uploadedFiles['file']);

	// Move file to temporary storage
	// $temporary_storage = new TemporaryStorage();
	// $directory = $temporary_storage->get_directory();

	// mkdir($directory, 0777, true);
	// $filename = 'file.zip';
	// $file->moveTo($directory . '/' . $filename);

	// $zipFile = new \PhpZip\ZipFile();
	// $zipFile->openFile($directory . '/' . $filename);
	// $listFiles = $zipFile->getListFiles();
	// print_r($listFiles);

	// $response->getBody()->write("Hello world! UUID: {$temporary_storage->get_directory()}");
	$response->getBody()->write("Hey there!");
	return $response;
});

$app->run();
