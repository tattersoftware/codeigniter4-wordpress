<?php namespace Tatter\WordPress\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Tatter\WordPress\Models\PostModel;

class PostsDelete extends BaseCommand
{
	protected $group       = 'WordPress';
	protected $name        = 'posts:delete';
	protected $description = 'Deletes the selected Post(s).';

	protected $usage     = 'posts:delete [postId]...';
	protected $arguments = [
		'postId' => 'The ID of the Post(s) to delete',
	];

	public function run(array $params)
	{
		// Make sure there is at least one ID
		$postId = array_shift($params);
		if (! is_numeric($postId))
		{
			$this->call('posts:list');
			CLI::write(lang('WordPress.commandMissingId'), 'red');
			CLI::write('Usage: php spark ' . $this->usage);
			return;
		}

		do
		{
			// Make sure the Post exists
			if (! $post = model(PostModel::class)->find($postId))
			{
				CLI::write(lang('WordPress.commandMissingPost', [$postId]), 'yellow');
			}
			else
			{
				model(PostModel::class)->delete($postId);
				CLI::write(lang('WordPress.postDeleted', [$post->post_title]), 'green');
			}
		} while ($postId = array_shift($params));
	}
}
