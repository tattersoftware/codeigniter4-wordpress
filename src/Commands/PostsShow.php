<?php namespace Tatter\WordPress\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Tatter\WordPress\Models\PostModel;

class PostsShow extends BaseCommand
{
	protected $group       = 'WordPress';
	protected $name        = 'posts:show';
	protected $description = 'Displays details about a single Post.';

	protected $usage     = 'posts:show [postId]';
	protected $arguments = [
		'postId' => 'The ID of the Post to display',
	];

	protected static function displayValue(string $name, $data, int $indent = 0)
	{
		$tabs = '';
		for ($i = $indent; $i > 0; $i--)
		{
			$tabs .= "\t";
		}

		if (! is_array($data))
		{
			CLI::write($tabs . CLI::color($name, 'white', null, 'underline') . ': ' . $data);
			return;
		}

		CLI::write($tabs . CLI::color($name, 'white', null, 'underline'));
		foreach ($data as $key => $value)
		{
			self::displayValue($key, $value, $indent + 1);
		}
	}

	public function run(array $params)
	{
		// Make sure there is an ID
		if (! $postId = array_shift($params))
		{
			$this->call('posts:list');
			CLI::write(lang('WordPress.commandMissingId'), 'red');
			CLI::write('Usage: php spark ' . $this->usage);
			return;
		}

		// Make sure there is a Post
		if (! $post = model(PostModel::class)->find($postId))
		{
			$this->call('posts:list');
			CLI::write(lang('WordPress.commandMissingPost', [$postId]), 'red');
			return;
		}
		helper('text');

		CLI::write('*** POST DETAILS ***', 'light_cyan');
		foreach ($post->toArray() as $key => $value)
		{
			if ($key === 'post_content')
			{
				continue;
			}

			$this->displayValue($key, $value);
		}
		CLI::write('');

		CLI::write('*** POST META ***', 'light_cyan');
		// Use getRows() to get all the keys
		foreach ($post->meta->getRows() as $meta)
		{
			$key = $meta['meta_key'];
			self::displayValue($key, $post->meta->$key);
		}
		CLI::write('');

		CLI::write('*** POST EXCERPT ***', 'light_cyan');
		$content = word_limiter(strip_tags($post->post_content));
		CLI::write(CLI::wrap($content, 60));
	}
}
