<?php namespace Tatter\WordPress\Test\Fakers;

use CodeIgniter\Test\Fabricator;
use Faker\Generator;
use Tatter\WordPress\Entities\Post;
use Tatter\WordPress\Models\PostModel;
use Tests\Support\DatabaseTestCase;

class PostFaker extends PostModel
{
	/**
	 * Faked data for Fabricator.
	 *
	 * @param Generator $faker
	 *
	 * @return Post
	 */
	public function fake(Generator &$faker): Post
	{
		$name = $faker->company . '.' . $faker->fileExtension;

		return new Post([
			'guid'                  => site_url($faker->word . '/' . $faker->word),
			'post_author'           => rand(1, Fabricator::getCount('users') ?: 10),
			'post_content'          => $faker->paragraph,
			'post_content_filtered' => $faker->paragraph,
			'post_excerpt'          => $faker->sentence,
			'post_mime_type'        => $faker->mimeType,
			'post_password'         => substr($faker->md5, 0, 20),
			'post_name'             => $faker->name,
			'post_parent'           => rand(0, 1) ? rand(0, Fabricator::getCount('posts')) : 0,
			'post_status'           => ['inherit', 'draft', 'publish'][rand(0,2)],
			'post_title'            => $faker->catchPhrase,
			'post_type'             => rand(0, 3) ? 'post' : 'page',
			'comment_count'         => rand(0, 3),
			'comment_status'        => rand(0, 3) ? 'open' : 'closed',
			'menu_order'            => rand(0, 20),
			'ping_status'           => rand(0, 3) ? 'open' : 'closed',
			'pinged'                => '',
			'to_ping'               => '',
		]);
	}
}
