<?php namespace Tatter\WordPress\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Tatter\WordPress\Models\PostModel;

class PostsList extends BaseCommand
{
	protected $group       = 'WordPress';
	protected $name        = 'posts:list';
	protected $usage       = 'posts:list';
	protected $description = 'Lists current WordPress posts.';

	public function run(array $params)
	{
		if (! model(PostModel::class)->first())
		{
			CLI::write('There are no posts.', 'yellow');
			return;
		}

		$thead = ['ID', 'Date', 'Title', 'Status', 'Type', 'Size'];
		$rows  = [];

		foreach (model(PostModel::class)->orderBy('post_date', 'asc')->find() as $post)
		{
			$rows[] = [
				$post->ID,
				$post->post_date->format('m/d/Y'),
				$post->post_title,
				$post->post_status,
				$post->post_type,
				''
			];
		}

		CLI::table($rows, $thead);
	}
}
