<?php namespace Tatter\WordPress;

use CodeIgniter\Database\MySQLi\Connection as MySQLiConnection;

class Connection extends MySQLiConnection
{
	/**
	 * Parse and store WordPress connection settings.
	 *
	 * @param array $params
	 */
	public function __construct(array $params)
	{
		
		parent::__construct($params);
	}
}
