<?php namespace Tatter\WordPress\Database;

use CodeIgniter\Database\Exceptions\DatabaseException;
use Tatter\WordPress\Libraries\Reader;

class Connection extends \CodeIgniter\Database\MySQLi\Connection
{
	/**
	 * Translation of WP to CI config
	 *
	 * @var array<string, string>
	 */
	protected static $readerKeys = [
		'DB_NAME'      => 'database',
		'DB_USER'      => 'username',
		'DB_PASSWORD'  => 'password',
		'DB_HOST'      => 'hostname',
		'DB_CHARSET'   => 'charset',
		'DB_COLLATE'   => 'DBCollat',
		'table_prefix' => 'DBPrefix',
	];

	/**
	 * Reader for the specific wp-config used
	 *
	 * @var Reader
	 */
	public $reader;

	/**
	 * Parses and stores WordPress connection settings.
	 *
	 * @param array $params
	 * @throws DatabaseException
	 */
	public function __construct(array $params)
	{
		if (empty($params['WPConfig']))
		{
			throw new DatabaseException('Missing WPConfig parameter for Tatter\WordPress database!');
		}

		// Use the Reader to extract from wp-config
		$this->reader = new Reader($params['WPConfig']);
		foreach (self::$readerKeys as $wp => $ci)
		{
			// Do not overwrite passed values
			if (isset($params[$ci]))
			{
				continue;
			}

			$params[$ci] = $this->reader->$wp;
		}

		// Create the class aliases
		self::aliasClasses();

		parent::__construct($params);
	}

	/**
	 * Aliases the companion classes to the MySQLi driver.
	 */
	private static function aliasClasses()
	{
		foreach (['Builder', 'Forge', 'PreparedQuery', 'Result', 'Utils'] as $name)
		{
			$original = 'CodeIgniter\Database\MySQLi\\' . $name;
			$alias    = 'Tatter\WordPress\Database\\' . $name;
			if (! class_exists($alias))
			{
				class_alias($original, $alias);
			}
		}
	}
}
