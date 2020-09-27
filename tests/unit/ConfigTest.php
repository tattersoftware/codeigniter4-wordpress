<?php

use Tatter\WordPress\Config;
use Tests\Support\WordPressTestCase;

class ConfigTest extends WordPressTestCase
{
	public function testParsesFile()
	{
		$config = new Config($this->configPath);

		$this->assertEquals('testing_database', $config->database);
	}
}
