<?php namespace Tatter\WordPress;

use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Database\MySQLi\Connection as MySQLiConnection;
use Tatter\WordPress\Config;

class Connection extends MySQLiConnection
{
	/**
	 * Parse and store WordPress connection settings.
	 *
	 * @param array $params
	 * @throws DatabaseException
	 */
	public function __construct(array $params)
	{
		if (empty($params['config']))
		{
			throw new DatabaseException('Missing config parameter for Tatter\WordPress database!');
		}

		// Use the Config parser to extract values
		$config = new Config($params['config']);

		// Make sure any parameters that were specified override the WordPress values
		$params = array_merge($config->toArray(), $params);

		parent::__construct($params);
	}
}
