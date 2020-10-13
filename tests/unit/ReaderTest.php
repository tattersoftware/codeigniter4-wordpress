<?php

use Tatter\WordPress\Exceptions\ReaderException;
use Tatter\WordPress\Libraries\Reader;
use Tests\Support\WordPressTestCase;

class ReaderTest extends WordPressTestCase
{
	public function testConstructParsesFile()
	{
		$reader = new Reader(SUPPORTPATH . 'wp-config.php');

		$this->assertEquals('testing_database', $reader->DB_NAME);
	}

	public function testNotFoundThrowsReaderException()
	{
		$file = 'foobar.php';

		$this->expectException(ReaderException::class);
		$this->expectExceptionMessage(lang('Files.fileNotFound', [$file]));

		$reader = new Reader($file);
	}

	public function testMissingPropertiesThrowsReaderException()
	{
		$file = SUPPORTPATH . 'wp-config.bad';

		$this->expectException(ReaderException::class);
		$this->expectExceptionMessage(lang('WordPress.readerParseFail', [$file]));

		$reader = new Reader($file);
	}

	public function testMissingABSPATHThrowsReaderException()
	{
		$reader = new Reader(SUPPORTPATH . 'wp-config.php');
		unset($reader->ABSPATH);

		$this->expectException(ReaderException::class);
		$this->expectExceptionMessage(lang('WordPress.readerDirectoryFail', [SUPPORTPATH . 'wp-config.php']));

		$reader->getDirectory();
	}

	public function testInvalidDirectoryThrowsReaderException()
	{
		$path = 'nonexistant/subdirectory';

		$reader = new Reader(SUPPORTPATH . 'wp-config.php');
		$reader->ABSPATH = $path;

		$this->expectException(ReaderException::class);
		$this->expectExceptionMessage(lang('Files.fileNotFound', [SUPPORTPATH . $path]));

		$reader->getDirectory();
	}
}
