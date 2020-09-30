<?php namespace Tatter\WordPress\Entities;

use CodeIgniter\Entity;
use Tatter\WordPress\Models\PostModel;
use Tatter\WordPress\Structures\MetaHandler;

class Post extends Entity
{
	protected $dates = ['post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt'];

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
