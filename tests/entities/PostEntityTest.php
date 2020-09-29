<?php

use Tatter\WordPress\Entities\Post;
use Tatter\WordPress\Models\PostModel;
use Tests\Support\WordPressTestCase;

class PostEntityTest extends WordPressTestCase
{
	/**
	 * @var PostModel
	 */
	protected $model;

	/**
	 * @var Post
	 */
	protected $post;

	protected function setUp(): void
	{
		parent::setUp();

		$this->model = new PostModel();
		$this->post  = $this->model->first();
	}

	public function testGetMetaReturnsStdClass()
	{
		$result = $this->post->meta;

		$this->assertInstanceOf('stdClass', $result);
	}

	public function testMetaHasValues()
	{
		$result = $this->post->meta;
dd($this->post);
		$this->assertInstanceOf('stdClass', $result);
	}
}
