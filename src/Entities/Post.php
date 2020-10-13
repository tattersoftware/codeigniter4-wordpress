<?php namespace Tatter\WordPress\Entities;

use CodeIgniter\Entity;
use Tatter\WordPress\Models\PostModel;
use Tatter\WordPress\Structures\MetaHandler;

class Post extends Entity
{
	protected $dates = ['post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt'];

    /**
     * Array of field names and the type of value to cast them as
     * when they are accessed.
     */
    protected $casts = [
        'post_author'   => 'int',
        'post_parent'   => 'int',
        'menu_order'    => 'int',
        'comment_count' => 'int',
    ];

	/**
	 * Default attributes.
	 *
	 * @var array<string, mixed>
	 */
	protected $attributes = [
		'post_author'    => 1,
		'post_content'   => '',
		'post_excerpt'   => '',
		'post_status'    => 'inherit',
		'comment_status' => 'open',
		'ping_status'    => 'closed',
		'post_parent'    => 0,
		'menu_order'     => 0,
		'comment_count'  => 0,
	];

	/**
	 * Handler for postmeta.
	 *
	 * @var MetaHandler|null
	 */
	protected $meta;

	/**
	 * Returns the MetaHandler. Uses the database connection
	 * from PostModel to be sure the group matches.
	 *
	 * @return MetaHandler
	 */
	public function getMeta(): MetaHandler
	{
		// If a MetaHandler is not set then initialize one
		if (is_null($this->meta))
		{
			$this->meta = new MetaHandler(
				model(PostModel::class)->db->table('postmeta'),
				['post_id' => $this->attributes['ID']]
			);
		}

		return $this->meta;
	}
}
