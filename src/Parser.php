<?php namespace Tatter\WordPress;

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;

/**
 * Parse class to extract database values from wp-config.php
 */
class Parser
{
	/**
	 * File instance for wp-config.php
	 *
	 * @var File
	 */
	protected $file;

	/**
	 * Parsed database configuration, compatible with
	 * app/Config/Database.php
	 *
	 * @var array
	 */
	protected $config = [
		'DSN'      => '',
		'hostname' => 'localhost',
		'username' => '',
		'password' => '',
		'database' => '',
		'DBDriver' => 'MySQLi',
		'DBPrefix' => '',
		'pConnect' => false,
		'DBDebug'  => (ENVIRONMENT !== 'production'),
		'charset'  => 'utf8',
		'DBCollat' => 'utf8_general_ci',
		'swapPre'  => '',
		'encrypt'  => false,
		'compress' => false,
		'strictOn' => false,
		'failover' => [],
		'port'     => 3306,
	];

	/**
	 * Verifies the config path, and loads it into a File, and
	 * parses out the database values.
	 *
	 * @param string $path
	 * @throws FileNotFoundException
	 */
	public function __construct(string $path)
	{
		$this->file = new File($path, true);

		$this->parse();
	}

	/**
	 * Parses database values from the file.
	 */
	protected function parse()
	{
		$lines = file($this->file->__toString());

		// Match lines like: define( 'DB_NAME', 'database_name_here' );
		$matched = preg_grep("/^define\( 'DB_/", $lines);

		// Explode each line and extract values
		$extracted = [];
		foreach ($matched as $line)
		{
			$array = explode("'", $line);
			$extracted[$array[1]] = $array[3];
		}
		dd($extracted);
	}
}
