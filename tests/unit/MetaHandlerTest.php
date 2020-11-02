<?php

use CodeIgniter\Database\BaseBuilder;
use Tatter\WordPress\Structures\MetaHandler;
use Tests\Support\WordPressTestCase;

class MetaHandlerTest extends WordPressTestCase
{
	/**
	 * @var BaseBuilder
	 */
	protected $builder;

	/**
	 * @var MetaHandler
	 */
	protected $meta;

	protected function setUp(): void
	{
		parent::setUp();

		$this->builder = db_connect('wordpress')->table('postmeta');
		$this->meta    = new MetaHandler($this->builder, ['post_id' => 2]);
	}

	public function testPrimaryKey()
	{
		$meta = new MetaHandler($this->builder, ['post_id' => 2]);
		$this->assertEquals('meta_id', $meta->primaryKey());

		$meta = new MetaHandler($this->builder, ['user_id' => 2]);
		$this->assertEquals('umeta_id', $meta->primaryKey());
	}

	public function testGetRows()
	{
		$result = $this->meta->getRows();

		$this->assertIsArray($result);
		$this->assertCount(1, $result);
	}

	public function testGet()
	{
		$result = $this->meta->get('_wp_page_template');

		$this->assertEquals('default', $result);
	}

	public function testMagicGet()
	{
		$result = $this->meta->_wp_page_template;

		$this->assertEquals('default', $result);
	}

	public function testGetEmpty()
	{
		$result = $this->meta->get('foobar');

		$this->assertNull($result);
	}

	public function testHas()
	{
		$result = $this->meta->has('_wp_page_template');

		$this->assertTrue($result);
	}

	public function testMagicHas()
	{
		$result = isset($this->meta->_wp_page_template);

		$this->assertTrue($result);
	}

	public function testHasEmpty()
	{
		$result = $this->meta->has('foobar');

		$this->assertFalse($result);
	}

	public function testAdd()
	{
		$expected = [
			'post_id'    => 2,
			'meta_key'   => 'foo',
			'meta_value' => 'bar',
			'meta_id'    => 3,
		];

		$result = $this->meta->add('foo', 'bar');

		$this->assertEquals($expected, $result);
	}

	public function testSerializes()
	{
		$expected = 'a:1:{s:3:"bar";s:3:"bam";}';

		$this->meta->foo = [
			'bar' => 'bam',
		];

		$rows   = $this->meta->getRows();
		$result = $rows[1]['meta_value'];

		$this->assertIsString($result);
		$this->assertEquals($expected, $result);
	}

	public function testUnserializes()
	{
		$expected = [
			'bar' => 'bam',
		];

		$this->meta->foo = $expected;

		$result = $this->meta->foo;

		$this->assertIsArray($result);
		$this->assertEquals($expected, $result);
	}
}
