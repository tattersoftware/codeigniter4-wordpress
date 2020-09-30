<?php

use Tatter\WordPress\Models\PostModel;
use Tests\Support\WordPressTestCase;

class PostModelTest extends WordPressTestCase
{
	/**
	 * @var PostModel
	 */
	protected $model;

	protected function setUp(): void
	{
		parent::setUp();

		$this->model = new PostModel();
	}

	public function testCanFind()
	{
		$result = $this->model->findAll();
		$this->assertCount(3, $result);
		$this->assertEquals('hello-world', $result[0]->post_name);
	}
}
