<?php namespace Tatter\WordPress\Test\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWordPressTables extends Migration
{
	public function up()
	{
		// posts
		$fields = [
			'ID'                    => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
			'post_author'           => ['type' => 'bigint', 'unsigned' => true],
			'post_date'             => ['type' => 'datetime'],
			'post_date_gmt'         => ['type' => 'datetime'],
			'post_content'          => ['type' => 'longtext'],
			'post_title'            => ['type' => 'text'],
			'post_excerpt'          => ['type' => 'text'],
			'post_status'           => ['type' => 'varchar', 'constraint' => 20],
			'comment_status'        => ['type' => 'varchar', 'constraint' => 20],
			'post_password'         => ['type' => 'varchar', 'constraint' => 20],
			'post_name'             => ['type' => 'varchar', 'constraint' => 200],
			'to_ping'               => ['type' => 'text'],
			'pinged'                => ['type' => 'text'],
			'post_modified'         => ['type' => 'datetime'],
			'post_modified_gmt'     => ['type' => 'datetime'],
			'post_content_filtered' => ['type' => 'longtext'],
			'post_parent'           => ['type' => 'bigint', 'unsigned' => true],
			'guid'                  => ['type' => 'varchar', 'constraint' => 255],
			'menu_order'            => ['type' => 'int', 'constraint' => 11],
			'post_type'             => ['type' => 'varchar', 'constraint' => 20],
			'post_mime_type'        => ['type' => 'varchar', 'constraint' => 100],
			'comment_count'         => ['type' => 'bigint', 'unsigned' => true],
		];
		$this->forge->addField($fields);

		$this->forge->addKey('ID', true);
		$this->forge->addKey('post_name');
		$this->forge->addKey(['post_type', 'post_status', 'post_date']);
		$this->forge->addKey('post_parent');
		$this->forge->addKey('post_author');
		
		$this->forge->createTable('posts');

		// postmeta
		$fields = [
			'meta_id'    => ['type' => 'bigint', 'unsigned' => true],
			'post_id'    => ['type' => 'bigint', 'unsigned' => true],
			'meta_key'   => ['type' => 'varchar', 'constraint' => 255],
			'meta_value' => ['type' => 'longtext'],
		];
		$this->forge->addField($fields);

		$this->forge->addKey('meta_id', true);
		$this->forge->addKey('post_id');
		$this->forge->addKey('meta_key');
		
		$this->forge->createTable('postmeta');
	}

	//--------------------------------------------------------------------

	public function down()
	{
		$this->forge->dropTable('posts');
		$this->forge->dropTable('postmeta');
	}
}
