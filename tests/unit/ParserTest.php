<?php

use Tatter\WordPress\Parser;
use Tests\Support\WordPressTestCase;

class ParserTest extends WordPressTestCase
{
	/**
	 * @var string
	 */
	protected $path;

	protected function setUp(): void
	{
		parent::setUp();

		// Locate the config file
		$this->path = $this->root->url() . '/wp-config-sample.php';
	}

	public function testParsesFile()
	{
		$parser = new Parser($this->path);

		$this->assertTrue(true);
	}
}
