<?php

use Tatter\WordPress\Database\Reader;
use Tests\Support\WordPressTestCase;

class ReaderTest extends WordPressTestCase
{
	public function testConstructParsesFile()
	{
		$reader = new Reader(SUPPORTPATH . 'wp-config.php');

		$this->assertEquals('testing_database', $reader->DB_NAME);
	}

	public function testToParamsTranslatesKeys()
	{
		$reader = new Reader(SUPPORTPATH . 'wp-config.php');
		$params = $reader->toParams();

		$this->assertEquals('testing_database', $params['database']);
	}
}
