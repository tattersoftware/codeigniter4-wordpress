<?php namespace Tatter\WordPress;

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;

/**
 * Class to extract database values from wp-config.php
 */
class Config
{
	/**
	 * Translation of WP to CI config
	 *
	 * @var array<string, string>
	 */
	protected $parseKeys = [
		'DB_NAME'      => 'database',
		'DB_USER'      => 'username',
		'DB_PASSWORD'  => 'password',
		'DB_HOST'      => 'hostname',
		'DB_CHARSET'   => 'charset',
		'DB_COLLATE'   => 'DBCollat',
		'table_prefix' => 'DBPrefix',
	];

	/**
	 * File instance for wp-config.php
	 *
	 * @var File
	 */
	protected $file;

	/**
	 * Array of extracted values.
	 *
	 * @var array<string, mixed>
	 */
	protected $parsed = [];

	/**
	 * Parsed database configuration, compatible with
	 * app/Config/Database.php
	 *
	 * @var array<string, mixed>
	 */
	protected $attributes = [];

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

		$this->parse()->convert();
	}

	/**
	 * Return parsed database configuration, compatible with
	 * app/Config/Database.php
	 *
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		return $this->parsed;
	}

	/**
	 * Parses database values from the file.
	 *
	 * @return $this
	 */
	protected function parse(): self
	{
		$lines = file($this->file->__toString());

		// Match lines like: define( 'DB_NAME', 'database_name_here' );
		$matched = preg_grep("/^define\(/", $lines);

		// Explode each line and extract values
		foreach ($matched as $line)
		{
			$array = explode("'", $line);
			if (count($array) === 5)
			{
				$this->parsed[$array[1]] = $array[3];
			}
		}

		// Grab the table prefix as well
		if ($matched = preg_grep("/^\$table_prefix/", $lines))
		{
			$array = explode("'", $lines[0]);
			if (count($array) === 3)
			{
				$this->parsed['table_prefix'] = $array[1];
			}
		}

		// If no table prefix was detected then use the default
		if (! isset($this->parsed['table_prefix']))
		{
			$this->parsed['table_prefix'] = 'wp_';
		}

		return $this;
	}

	/**
	 * Converts parsed WordPress keys to CodeIgniter ones.
	 */
	protected function convert()
	{
		foreach ($this->parseKeys as $from => $to)
		{
			if (isset($this->parsed[$from]))
			{
				$this->attributes[$to] = $this->parsed[$from];
			}
		}

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Magic method to allow retrieval of attributes.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get(string $key)
	{
		if (array_key_exists($key, $this->attributes))
		{
			return $this->attributes[$key];
		}

		return null;
	}

	//--------------------------------------------------------------------

	/**
	 * Magic method to all setting properties.
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function __set(string $key, $value = null): self
	{
		$this->attributes[$key] = $value;

		return $this;
	}

	/**
	 * Unsets an attribute property.
	 *
	 * @param string $key
	 */
	public function __unset(string $key)
	{
		unset($this->attributes[$key]);
	}

	/**
	 * Returns true if the $key attribute exists.
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function __isset(string $key): bool
	{
		return isset($this->attributes[$key]);
	}
}
