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
	 * Path to the virtualized WordPress config file
	 *
	 * @var string
	 */
	protected $WPConfig;

	protected function setUp(): void
	{
		parent::setUp();

		Config::reset();

		// Start the virtual filesystem
		$this->root = vfsStream::setup();

		// Copy in WordPress core from Composer
		vfsStream::copyFromFileSystem(HOMEPATH . 'vendor/johnpbloch/wordpress-core', $this->root);

		// Inject our WordPress config file
		$this->WPConfig = $this->root->url() . '/wp-config.php';
		copy(SUPPORTPATH . 'wp-config.php', $this->WPConfig);

		// Set up our test database configuration
		$config = config('Database');
		$config->wordpress = [
			'DBDriver' => 'Tatter\WordPress\Database',
			'WPConfig' => SUPPORTPATH . 'wp-config.php',
		];
		Config::injectMock('Database', $config);
	}

	protected function tearDown(): void
	{
		parent::tearDown();

		$this->root = null;
	}
}
