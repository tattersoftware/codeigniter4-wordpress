<?php namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class WordPressTestCase extends CIUnitTestCase
{
	/**
	 * Configuration
	 *
	 * @var WordPress
	 */
	protected $config;

	/**
	 * @var vfsStreamDirectory|null
	 */
	protected $root;

	protected function setUp(): void
	{
		parent::setUp();

		// Start the virtual filesystem
		$this->root = vfsStream::setup();

		// Copy in WordPress core from Composer
		vfsStream::copyFromFileSystem(HOMEPATH . 'vendor/johnpbloch/wordpress-core', $this->root);
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		$this->root = null;
	}
}
