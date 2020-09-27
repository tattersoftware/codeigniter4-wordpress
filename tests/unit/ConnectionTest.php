<?php

use CodeIgniter\Config\Config;
use Tatter\WordPress\Database\Connection;
use Tests\Support\WordPressTestCase;

class ConnectionTest extends WordPressTestCase
{
	protected function setUp(): void
	{
		parent::setUp();

		// Force this test to use our driver
		$config = config('Database');
		$config->wordpress = [
			'DBDriver' => 'Tatter\WordPress\Database',
			'WPConfig' => $this->WPConfig,
		];

		Config::injectMock('Database', $config);
	}

	public function testReturnsInstance()
	{
		$result = config('Database')::connect('wordpress', false);

		$this->assertInstanceOf(Connection::class, $result);
	}
}
