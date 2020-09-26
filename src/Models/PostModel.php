<?php namespace Tatter\WordPress\Models;

use Tatter\WordPress\BaseModel;

class PostModel extends BaseModel
{
	protected $table      = 'posts';
	protected $returnType = 'Tatter\WordPress\Entities\Post';

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
}
