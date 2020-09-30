<?php namespace Tatter\WordPress\Entities;

use CodeIgniter\Entity;
use Tatter\WordPress\Models\PostModel;

class Post extends Entity
{
	protected $dates = ['post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt'];

	/**
	 * Cache for rows from postmeta.
	 *
	 * @var stdClass|null
	 */
	protected $meta;

	/**
	 * Returns or loads metadata from the related table.
	 *
	 * @return stdClass|null
	 */
	protected function getMeta(): ?\stdClass
	{
		// If meta is not set then laod it on the fly
		if ($this->meta === null)
		{
			$this->meta = new \stdClass();

			foreach (model(PostModel::class)
				->builder('postmeta')
				->where(['post_id' => $this->attributes['ID']])
				->get()->getResultArray() as $row)
			{
				$this->meta->$row['meta_key'] = $row['meta_value'];
			}
		}

		return $this->meta;
	}
}
