<?php namespace Tatter\WordPress\Entities;

use CodeIgniter\Entity;

class Post extends Entity
{
	protected $dates = ['post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt'];
}
