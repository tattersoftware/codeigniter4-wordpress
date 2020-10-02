<?php namespace Tatter\WordPress\Structures;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * A wrapper class to ease entity reading and writing
 * of related meta rows.
 */
class MetaHandler
{
	/**
	 * Query Builder object
	 *
	 * @var BaseBuilder|null
	 */
	protected $builder;

	/**
	 * The primary key criterium for the originating entity.
	 * E.g. for Post with ID of 4 ['post_id' => 4]
	 *
	 * @var array<string, int>
	 */
	protected $filter;

	/**
	 * Cache for the actual data.
	 *
	 * @var array[]|null Array of raw array rows return from $builder
	 */
	protected $rows;

	/**
	 * Stores the table-specific builder to use.
	 *
	 * @param BaseBuilder $builder       A Builder for the model's group and the meta table
	 * @param array<string, int> $filter Primary key criterium for the originating entity
	 * @param array|null $rows           Preloaded data to inject
	 */
	public function __construct(BaseBuilder $builder, array $filter, array $rows = null)
	{
		$this->builder = $builder;
		$this->filter  = $filter;
		$this->rows    = $rows;
	}

	/**
	 * Determines the primary key for this meta table,
	 * because stupid `usermeta` has a different one.
	 *
	 * @return string
	 */
	public function primaryKey(): string
	{
		return isset($this->filter['user_id']) ? 'umeta_id' : 'meta_id';
	}

	/**
	 * Returns the raw $rows array.
	 *
	 * @return array
	 */
	public function getRows(): array
	{
		return $this->fetch()->rows;
	}

	/**
	 * Fetches the data if it has not been loaded. Called at the last possible
	 * moment to minimize redudant work.
	 *
	 * @return $this
	 */
	protected function fetch(): self
	{
		if (is_null($this->rows))
		{
			$this->rows = $this->builder
				->where($this->filter)
				->get()->getResultArray();
		}

		return $this;
	}

	/**
	 * Returns the index to the row holding meta_key, if it exists.
	 *
	 * @param string $key
	 *
	 * @return int|null
	 */
	protected function find(string $key): ?int
	{
		$this->fetch();

		foreach ($this->rows as $i => $row)
		{
			if ($row['meta_key'] === $key)
			{
				return $i;
			}
		}

		return null;
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the meta_value for a key, if it exists.
	 * Since meta_value is nullable, a null return
	 * could indicate either "not found" or "null" -
	 * use has() if actual existence matters.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get(string $key)
	{
		if (is_null($i = $this->find($key)))
		{
			return null;
		}

		return $this->rows[$i]['meta_value'];
	}

	/**
	 * Returns true if a meta_key exists named $key.
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function has(string $key): bool
	{
		return is_int($this->find($key));
	}

	/**
	 * Adds a key-value pair.
	 *
	 * @param string     $key
	 * @param mixed|null $value
	 *
	 * @return array<string, mixed>  The new row
	 *
	 * @throws DatabaseException
	 */
	public function add(string $key, $value = null): array
	{
		$this->fetch();

		// Build the new row
		$row               = $this->filter;
		$row['meta_key']   = $key;
		$row['meta_value'] = $value;

		// Insert it
		if (! $this->builder->insert($row))
		{
			$error = $this->builder->db()->error();
			throw new DatabaseException($error['message']);
		}

		// Add the new ID to the row and cache it
		$row[$this->primaryKey()] = $this->builder->db()->insertID(); // @phpstan-ignore-line
		$this->rows[]             = $row;

		return $row;
	}

	/**
	 * Updates a key to a value.
	 *
	 * @param string     $key
	 * @param mixed|null $value
	 *
	 * @return array<string, mixed>  The updated row
	 *
	 * @throws DataException
	 * @throws DatabaseException
	 */
	public function update(string $key, $value = null): array
	{
		if (is_null($i = $this->find($key)))
		{
			throw new DataException('Key does not exist: ' . $key);
		}

		// Build the inputs
		$where = [
			$this->primaryKey() => $this->rows[$i][$this->primaryKey()],
		];
		$set = [
			'meta_value' => $value,
		];

		// Update it
		if (! $this->builder->update($set, $where))
		{
			$error = $this->builder->db()->error();
			throw new DatabaseException($error['message']);
		}

		// Change the cached value
		$this->rows[$i]['meta_value'] = $value;

		return $this->rows[$i];
	}

	/**
	 * Deletes the first occurence of a key.
	 *
	 * @param string $key
	 *
	 * @throws DataException
	 * @throws DatabaseException
	 */
	public function delete(string $key)
	{
		if (is_null($i = $this->find($key)))
		{
			throw new DataException('Key does not exist: ' . $key);
		}

		// Build the inputs
		$where = [
			$this->primaryKey() => $this->rows[$i][$this->primaryKey()],
		];

		// Delete it
		if (! $this->builder->delete($where))
		{
			$error = $this->builder->db()->error();
			throw new DatabaseException($error['message']);
		}

		// Remove the cached row
		unset($this->rows[$i]);
	}

	//--------------------------------------------------------------------

	/**
	 * Returns the meta_value of the meta_key matching $key.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get(string $key)
	{
		return $this->get($key);
	}

	/**
	 * Returns true if a meta_key exists named $key.
	 *
	 * @param string $key
	 *
	 * @return boolean
	 */
	public function __isset(string $key): bool
	{
		return $this->has($key);
	}

	/**
	 * Adds or updates a row.
	 *
	 * @param string      $key
	 * @param mixed|null $value
	 *
	 * @return $this
	 *
	 * @throws DatabaseException
	 */
	public function __set(string $key, $value = null): self
	{
		// Add or update for a new value
		$this->has($key) ? $this->update($key, $value) : $this->add($key, $value);

		return $this;
	}

	/**
	 * Removes and deletes a row.
	 *
	 * @param string $key
	 *
	 * @throws DatabaseException
	 */
	public function __unset(string $key)
	{
		$this->has($key) && $this->delete($key);
	}
}
