<?php

use Tatter\WordPress\Config;
use Tests\Support\WordPressTestCase;

class ConfigTest extends WordPressTestCase
{
	/**
	 * @var string
	 */
	protected $path;

	protected function setUp(): void
	{
		parent::setUp();

		// Locate the sample config file
		$this->path = $this->root->url() . '/wp-config-sample.php';
	}

	public function testParsesFile()
	{
		$config = new Config($this->path);

		$this->assertEquals('database_name_here', $config->database);
	}
}
