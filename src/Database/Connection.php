<?php namespace Tatter\WordPress\Database;

use CodeIgniter\Database\Exceptions\DatabaseException;

class Connection extends \CodeIgniter\Database\MySQLi\Connection
{
	/**
	 * Aliases the companion classes to their MySQLi equivalents.
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
			throw new DatabaseException('Missing config parameter for Tatter\WordPress database!');
		}

		// Use the reader to extract values
		$reader = new ConfigReader($params['WPConfig']);

		// Make sure any parameters that were specified override the WordPress values
		$params = array_merge($reader->toParams(), $params);

		// Create the class aliases
		self::aliasClasses();

		parent::__construct($params);
	}
}
