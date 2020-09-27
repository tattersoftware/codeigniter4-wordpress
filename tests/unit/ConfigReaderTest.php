<?php

use Tatter\WordPress\Database\ConfigReader;
use Tests\Support\WordPressTestCase;

class ConfigReaderTest extends WordPressTestCase
{
	public function testParsesFile()
	{
		$reader = new ConfigReader($this->WPConfig);

		$this->assertEquals('testing_database', $reader->database);
	}
}
