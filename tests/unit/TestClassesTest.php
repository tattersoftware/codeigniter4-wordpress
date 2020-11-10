<?php

use CodeIgniter\Test\CIDatabaseTestCase;
use CodeIgniter\Test\Fabricator;
use Tatter\WordPress\Entities\Post;
use Tatter\WordPress\Test\Fakers\PostFaker;

class TestClassesTest extends CIDatabaseTestCase
{
	/**
	 * @var boolean
	 */
	protected $refresh = false;

	public function testMigrationCreatesTables()
	{
		$expected = [
			'db_migrations',
			'db_posts',
			'db_postmeta',
		];

		// Run the test migratation against the `tests` group
		$this->migrations->force(HOMEPATH . 'src/Test/Database/Migrations/2020-11-07-023559_CreateWordPressTables.php', 'Tatter\WordPress', 'tests');
		$result = db_connect()->listTables(true);

		$this->assertEquals($expected, array_values($result));
	}

	public function testPostFakerInserts()
	{
		$faker      = new PostFaker();
		$fabricator = new Fabricator($faker);

		/** @var Post $result */
		$result = $fabricator->create();
		$this->assertInstanceOf(Post::class, $result);

		$this->db = db_connect('wordpress');
		$this->seeInDatabase('posts', ['post_title' => $result->post_title]);
		$this->db = db_connect($this->DBGroup);
	}
}
