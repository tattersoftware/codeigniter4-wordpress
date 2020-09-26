<?php namespace Tatter\WordPress;

use CodeIgniter\Model;

abstract class BaseModel extends Model
{
	/**
	 * The Database connection group that
	 * should be instantiated.
	 *
	 * @var string
	 */
	protected $DBGroup = 'wordpress';

	protected $primaryKey = 'ID';
	protected $returnType = 'object';

	protected $useTimestamps  = false;
	protected $useSoftDeletes = false;
	protected $skipValidation = false;
}
