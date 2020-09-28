<?php

use Tests\Support\MockModel;
use Tests\Support\WordPressTestCase;

class BaseModelTest extends WordPressTestCase
{
	/**
	 * @var MockModel
	 */
	protected $model;

	protected function setUp(): void
	{
		parent::setUp();

		$this->model = new MockModel();
	}

	public function testGetsDatabase()
	{
		$this->assertTrue(true);
	}
}
