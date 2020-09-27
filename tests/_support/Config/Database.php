<?php namespace Tests\Support\Config;

use Config\Database as BaseConfig;

/**
 * WordPress Database Configuration
 */
class Database extends BaseConfig
{
	/**
	 * Example WordPress database connection.
	 *
	 * @var array
	 */
	public $wordpress = [
		'DBDriver' => 'Tatter\WordPress',
		'config'   => SUPPORTPATH . 'wp-config.php',
	];
}
