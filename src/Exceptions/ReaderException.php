<?php namespace Tatter\WordPress\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

class ReaderException extends \RuntimeException implements ExceptionInterface
{
	public static function forParseFail($path)
	{
		return new static(lang('WordPress.readerParseFail', [$path]));
	}

	public static function forDirectoryFail($path)
	{
		return new static(lang('WordPress.readerDirectoryFail', [$path]));
	}
}
