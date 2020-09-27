<?php

use Tatter\WordPress\Database\ConfigReader;
use Tests\Support\WordPressTestCase;

class ConfigReaderTest extends WordPressTestCase
{
	public function testConstructParsesFile()
	{
		$reader = new ConfigReader(SUPPORTPATH . 'wp-config.php');

		$this->assertEquals('testing_database', $reader->DB_NAME);
	}

	public function testToParamsTranslatesKeys()
	{
		$reader = new ConfigReader(SUPPORTPATH . 'wp-config.php');
		$params = $reader->toParams();

		$this->assertEquals('testing_database', $params['database']);
	}
}
