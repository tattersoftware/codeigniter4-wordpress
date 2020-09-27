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

	public function testGetsFields()
	{
		$result = $this->model->findAll();

		$this->assertTrue(true);
	}
}
