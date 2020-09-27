<?php

use Tatter\WordPress\Connection;
use Tests\Support\WordPressTestCase;

class ConnectionTest extends WordPressTestCase
{
	public function testParsesFile()
	{
		$result = db_connect('wordpress');

		$this->assertInstanceOf(Connection::class, $result);
	}
}
