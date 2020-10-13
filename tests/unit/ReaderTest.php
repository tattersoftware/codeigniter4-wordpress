<?php

use Tatter\WordPress\Libraries\Reader;
use Tests\Support\WordPressTestCase;

class ReaderTest extends WordPressTestCase
{
	public function testConstructParsesFile()
	{
		$reader = new Reader(SUPPORTPATH . 'wp-config.php');

		$this->assertEquals('testing_database', $reader->DB_NAME);
	}
}
