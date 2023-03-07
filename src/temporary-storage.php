<?php

use Ramsey\Uuid\Uuid;

class TemporaryStorage {
	public function get_directory() {
		$uuid = Uuid::uuid4()->toString();
		return $_SERVER["DOCUMENT_ROOT"] . '/../tmp/' . $uuid;
	}
}