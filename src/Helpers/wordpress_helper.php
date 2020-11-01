<?php

/**
 * WordPress Helper Functions
 *
 * These functions are restyled versions of
 * WordPress core functions.
 */

if (! function_exists('maybe_serialize'))
{
	/**
	 * Serialize data, if needed.
	 *
	 * @param string|array|object $data Data that might be serialized
	 *
	 * @return mixed
	 * @see https://developer.wordpress.org/reference/functions/maybe_serialize/
	 */
	function maybe_serialize($data)
	{
		if (is_array($data) || is_object($data))
		{
			return serialize($data);
		}
 
		/*
		 * Double serialization is required for backward compatibility.
		 * See https://core.trac.wordpress.org/ticket/12930
		 * Also the world will end. See WP 3.6.1.
		 */
		if (is_serialized($data, false))
		{
			return serialize($data);
		}

		return $data;
	}
}

if (! function_exists('maybe_unserialize'))
{
	/**
	 * Deserializes a value if necessary.
	 *
	 * @param mixed $data
	 *
	 * @return mixed
	 * @see https://developer.wordpress.org/reference/functions/maybe_unserialize/
	 */
	function maybe_unserialize($data)
	{
		// Don't attempt to unserialize data that wasn't serialized going in.
		if (is_serialized($data))
		{
			return @unserialize(trim($data));
		}

		return $data;
	}
}

if (! function_exists('is_serialized'))
{
	/**
	 * Deteremines whether data is serialized.
	 * Borrowed from WordPress core functions.
	 *
	 * @param mixed $data Value to check to see if was serialized
	 * @param bool $strict Whether to be strict about the end of the string
	 *
	 * @return bool
	 * @see https://developer.wordpress.org/reference/functions/is_serialized/
	 */
	function is_serialized($data, $strict = true): bool
	{
		// If it isn't a string, it isn't serialized.
		if (! is_string($data))
		{
			return false;
		}
		$data = trim($data);

		if ('N;' === $data)
		{
			return true;
		}
		if (strlen($data) < 4)
		{
			return false;
		}
		if (':' !== $data[1])
		{
			return false;
		}

		if ($strict)
		{
			$lastc = substr($data, -1);
			if (';' !== $lastc && '}' !== $lastc)
			{
				return false;
			}
		}
		else
		{
			$semicolon = strpos($data, ';');
			$brace	   = strpos($data, '}');

			// Either ; or } must exist.
			if (false === $semicolon && false === $brace)
			{
				return false;
			}
			// But neither must be in the first X characters.
			if (false !== $semicolon && $semicolon < 3)
			{
				return false;
			}
			if (false !== $brace && $brace < 4)
			{
				return false;
			}
		}

		$token = $data[0];
		switch ($token)
		{
			case 's':
				if ($strict)
				{
					if ('"' !== substr($data, -2, 1))
					{
						return false;
					}
				}
				elseif (false === strpos($data, '"'))
				{
					return false;
				}

			// Or else fall through.
			case 'a':
			case 'O':
				return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
			case 'b':
			case 'i':
			case 'd':
				$end = $strict ? '$' : '';
				return (bool) preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
		}

		return false;
	}
}
