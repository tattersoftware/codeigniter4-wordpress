<?php namespace Tatter\WordPress\Database;

use CodeIgniter\Database\Exceptions\DatabaseException;

class Connection extends \CodeIgniter\Database\MySQLi\Connection
{
	/**
	 * Parse and store WordPress connection settings.
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

		parent::__construct($params);
	}
}
