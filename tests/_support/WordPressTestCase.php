<?php namespace Tests\Support;

use CodeIgniter\Config\Config;
use CodeIgniter\Test\CIUnitTestCase;
use Tests\Support\Config\Database;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class WordPressTestCase extends CIUnitTestCase
{
	/**
	 * @var vfsStreamDirectory|null
	 */
	protected $root;

	/**
	 * Path to the virtualized config file
	 *
	 * @var string
	 */
	protected $configPath;

	protected function setUp(): void
	{
		parent::setUp();

		Config::reset();

		// Start the virtual filesystem
		$this->root = vfsStream::setup();

		// Copy in WordPress core from Composer
		vfsStream::copyFromFileSystem(HOMEPATH . 'vendor/johnpbloch/wordpress-core', $this->root);

		// Inject our WordPress config file
		$this->configPath = $this->root->url() . '/wp-config.php';
		copy(SUPPORTPATH . 'wp-config.php', $this->configPath);

		// Use our test database configuration
		Config::injectMock('Database', new Database());
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		$this->root = null;
	}
}
