<?php namespace Tests\Support;

use CodeIgniter\Config\Config;
use CodeIgniter\Test\CIUnitTestCase;
use Tests\Support\Config\Database;

class WordPressTestCase extends CIUnitTestCase
{
	/**
	 * Path to the WordPress config file
	 *
	 * @var string
	 */
	protected $WPConfig = WORDPRESSPATH . 'wp-config.php';

	protected function setUp(): void
	{
		parent::setUp();
		Config::reset();

		$this->addDBGroup();
	}

	/**
	 * Sets up the wordpress database group.
	 */
	protected function addDBGroup()
	{
		$config                        = config('Database');
		$config->wordpress['DBDriver'] = 'Tatter\WordPress\Database';
		$config->wordpress['WPConfig'] = $this->WPConfig;

		Config::injectMock('Database', $config);
	}
}
