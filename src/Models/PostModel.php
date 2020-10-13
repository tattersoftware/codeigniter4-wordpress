<?php namespace Tatter\WordPress\Models;

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use Tatter\WordPress\Entities\Post;

class PostModel extends BaseModel
{
	protected $table      = 'posts';
	protected $primaryKey = 'ID';
	protected $returnType = 'Tatter\WordPress\Entities\Post';

	protected $createdField  = 'post_date';
	protected $updatedField  = 'post_modified';
	protected $allowedFields = [
		'guid',
		'post_author',
		'post_date',
		'post_date_gmt',
		'post_content',
		'post_content_filtered',
		'post_excerpt',
		'post_mime_type',
		'post_modified',
		'post_modified_gmt',
		'post_password',
		'post_name',
		'post_parent',
		'post_status',
		'post_title',
		'post_type',
		'comment_count',
		'comment_status',
		'menu_order',
		'ping_status',
		'pinged',
		'to_ping',
	];

	protected $validationRules = [
		'post_status'    => 'permit_empty|max_length[20]',
		'post_password'  => 'permit_empty|max_length[20]',
		'post_name'      => 'permit_empty|max_length[200]',
		'post_type'      => 'permit_empty|max_length[20]',
		'post_mime_type' => 'permit_empty|max_length[100]',
		'ping_status'    => 'permit_empty|max_length[20]',
		'comment_status' => 'permit_empty|max_length[20]',
		'guid'           => 'permit_empty|max_length[255]',
	];

	/**
	 * Returns an "attachment" type post from a file path.
	 * Moves the file if it is not already in the WordPress directory.
	 * Does not insert into the database.
	 *
	 * @param string $path Path to the file
	 *
	 * @return Post
	 *
	 * @throws FileNotFoundException, \RuntimeException
	 */
	public function fromFile(string $path): Post
    {
    	$path = realpath($path) ?: $path;

    	// Get and verify the file and target folder
    	$file = new File($path, true);
		$base = $this->db->reader->getDirectory();
		$dir  = $base . 'wp-content' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR;

		// Determine if we need to move the file
		if (strpos($path, $base) === false)
		{
			// Make sure the directory is there
			if (! is_dir($dir) && ! mkdir($dir, 0775, true))
			{
				throw new \RuntimeException('Unable to create destination for file move: ' . $dir);
			}

			// Move the file and set permissions
			$file = $file->move($dir);
			chmod((string) $file, 0664);

			$path = $file->getRealPath() ?: (string) $file;
		}

		// Build the Post
		return new Post([
			'post_type'      => 'attachment',
			'post_title'     => $file->getFilename(),
			'post_name'      => $file->getFilename(),
			'post_mime_type' => $file->getMimeType(),
			'guid'           => base_url(str_replace($base, '', $path)),
		]);
    }
}
