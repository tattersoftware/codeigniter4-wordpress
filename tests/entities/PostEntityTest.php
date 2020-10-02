<?php

use Tatter\WordPress\Entities\Post;
use Tatter\WordPress\Models\PostModel;
use Tatter\WordPress\Structures\MetaHandler;
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

	public function testGetMetaReturnsHandler()
	{
		$result = $this->post->getMeta();

		$this->assertInstanceOf(MetaHandler::class, $result);
	}
}
