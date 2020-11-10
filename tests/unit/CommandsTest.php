<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\Filters\CITestStreamFilter;
use Tatter\WordPress\Models\PostModel;
use Tests\Support\WordPressTestCase;

class CommandsTest extends WordPressTestCase
{
	protected $streamFilter;

	protected function setUp(): void
	{
		CITestStreamFilter::$buffer = '';

		$this->streamFilter = stream_filter_append(STDOUT, 'CITestStreamFilter');
		$this->streamFilter = stream_filter_append(STDERR, 'CITestStreamFilter');

		parent::setUp();
	}

	protected function tearDown(): void
	{
		stream_filter_remove($this->streamFilter);

		parent::tearDown();
	}

	protected function getBuffer(): string
	{
		return CITestStreamFilter::$buffer;
	}

	protected function clearBuffer(): void
	{
		CITestStreamFilter::$buffer = '';
	}

	public function testList()
	{
		command('posts:list');
		$result = $this->getBuffer();

		$this->assertStringContainsString('Hello world!', $result);
		$this->assertStringContainsString('draft', $result);
	}

	public function testShow()
	{
		command('posts:show 1');
		$result = $this->getBuffer();

		$this->assertStringContainsString('post_password', $result);
		$this->assertStringContainsString('POST META', $result);
		$this->assertStringContainsString('Welcome to WordPress', $result);
	}

	public function testDelete()
	{
		if (getenv('CI'))
		{
			$this->markTestSkipped('Inserts are failing inexplicably during GitHub Actions');
		}

		// Create a temporary Post to remove
		$postId = model(PostModel::class)->insert([
			'post_title' => 'foobar',
		]);
		$this->assertIsInt($postId);

		command('posts:delete ' . (string) $postId);
		$result = $this->getBuffer();
		$this->assertStringContainsString(lang('WordPress.postDeleted', ['foobar']), $result);

		$result = model(PostModel::class)->find($postId);
		$this->assertNull($result);
	}
}
