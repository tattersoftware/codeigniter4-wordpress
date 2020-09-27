<?php

use Tatter\WordPress\Database\Connection;
use Tests\Support\WordPressTestCase;

class ConnectionTest extends WordPressTestCase
{
	public function testGroupUsesDriver()
	{
		$result = db_connect('wordpress');

		$this->assertInstanceOf(Connection::class, $result);
	}

	public function testEstablishesConnection()
	{
		$db     = db_connect('wordpress');
		$result = $db->listTables(true);

		$this->assertIsArray($result);
		$this->assertContains('wp_posts', $result);
	}
}
