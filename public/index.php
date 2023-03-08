<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\ContainerBuilder;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$container = $containerBuilder->build();
$container->set('upload_directory', $_SERVER["DOCUMENT_ROOT"] . '/tmp/webserver');
AppFactory::setContainer($container);
$app = AppFactory::create();

function parse_wp_package($fileLocation)
{
	$package = new Max_WP_Package($fileLocation);
	$packageMetadata = $package->get_metadata();
	$packageMetadata['type'] = $package->get_type();
	$packageMetadata['hash'] = hash_file('sha256', $fileLocation);
	return $packageMetadata;
}

$app->post('/', function (Request $request, Response $response, $args) {
	# Check uploaded files
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

	# Move file to temporary storage
	$uuid = Uuid::uuid4()->toString();
	$temporaryDirectory = $_SERVER["DOCUMENT_ROOT"] . '/tmp/' . $uuid;
	mkdir($temporaryDirectory, 0777, true);
	$filename = 'file.zip';
	$file->moveTo($temporaryDirectory . '/' . $filename);

	# Open zip and get list of files
	$zipFile = new \PhpZip\ZipFile();
	$zipFile->openFile($temporaryDirectory . '/' . $filename);
	$listFiles = $zipFile->getListFiles();

	# Check if there others zip files inside the zip
	$listOfZipFiles = array_filter($listFiles, function ($value) {
		$extension = substr($value, -4);
		return $extension === ".zip";
	});

	$results = [];

	if (count($listOfZipFiles) >= 1) {
		$zipFile->extractTo($temporaryDirectory, $listOfZipFiles);

		foreach ($listOfZipFiles as $file) {
			$packageMetadata = parse_wp_package($temporaryDirectory . '/' . $file);
			$packageMetadata['location'] = $file;

			if ($packageMetadata['type'] !== 'null') {
				$results[] = $packageMetadata;
			}
		}
	} else {
		$packageMetadata = parse_wp_package($temporaryDirectory . '/' . $filename);

		if ($packageMetadata['type'] !== 'null') {
			$results[] = $packageMetadata;
		}
	}

	# Remove the temporary directory
	rmrdir($temporaryDirectory);

	# Encode and return the json
	$response->getBody()->write(json_encode($results));
	return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
