<?php namespace Tatter\WordPress\Libraries;

use CodeIgniter\Files\Exceptions\FileNotFoundException;
use CodeIgniter\Files\File;
use Tatter\WordPress\Exceptions\ReaderException;

/**
 * Class to extract values from wp-config.php
 */
class Reader
{
	/**
	 * File instance for wp-config.php
	 *
	 * @var File
	 */
	protected $file;

	/**
	 * The extracted values
	 *
	 * @var array<string, mixed>
	 */
	protected $attributes = [];

	/**
	 * Verifies the config path, loads it into a File, and
	 * parses out the values.
	 *
	 * @param string $path
	 * @throws ReaderException
	 */
	public function __construct(string $path)
	{
		// Catch file exceptions to re-throw as ReaderException
		try
		{
			$this->file = new File($path, true);
		}
		catch (FileNotFoundException $e)
		{
			throw new ReaderException($e->getMessage(), $e->getCode(), $e);
		}

		$this->parse();

		// Make sure a minimum number of properties were detected as a sanity check
		if (count($this->attributes) < 8)
		{
			throw ReaderException::forParseFail($path);
		}
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
		$matched = preg_grep("/define\(/", $lines);

		// Explode each line and extract values
		foreach ($matched as $line)
		{
			$array = explode("'", $line);
			if (count($array) === 5)
			{
				$this->attributes[$array[1]] = $array[3];
			}
		}

		// Grab the table prefix as well
		if ($matched = preg_grep("/^\$table_prefix/", $lines))
		{
			$array = explode("'", $lines[0]);
			if (count($array) === 3)
			{
				$this->attributes['table_prefix'] = $array[1];
			}
		}

		// If no table prefix was detected then use the default
		if (! isset($this->attributes['table_prefix']))
		{
			$this->attributes['table_prefix'] = 'wp_';
		}

		return $this;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns this File instance for the
	 * wp-config.php used.
	 *
	 * @return File
	 */
	public function getFile(): File
	{
		return $this->file;
	}

	/**
	 * Deteremines the WordPress installation
	 * directory using ABSPATH.
	 *
	 * @return string
	 * @throws ReaderException
	 */
	public function getDirectory(): File
	{
		if (! isset($this->attributes['ABSPATH']))
		{
			throw ReaderException::forDirectoryFail($this->file->__toString());
		}

		$path = $this->file->getPath() . DIRECTORY_SEPARATOR . $this->attributes['ABSPATH'];
		$path = realpath($path) ?: $path;
		$path = rtrim($path, '/\\ ') . DIRECTORY_SEPARATOR;

		if (! is_dir($path))
		{
			$e = FileNotFoundException::forFileNotFound($path);
			throw new ReaderException($e->getMessage(), $e->getCode(), $e);
		}

		return $path;
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

	/**
	 * Magic method for setting properties.
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
