<?php

use Ramsey\Uuid\Uuid;

class TemporaryStorage {
	public function get_directory() {
		return __DIR__ . '/tmp/' . Uuid::uuid4()->toString();
	}
}