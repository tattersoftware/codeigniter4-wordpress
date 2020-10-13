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
	}
}
