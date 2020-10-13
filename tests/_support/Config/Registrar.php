<?php namespace Tests\Support\Config;

class Registrar
{
	/**
	 * Adds a WordPress database group for testing
	 *
	 * @return array
	 */
	public static function Database()
	{
		return [
			'wordpress' => [
				'DBDriver' => 'Tatter\WordPress\Database',
				'WPConfig' => WORDPRESSPATH . 'wp-config.php',
			],
		];
	}
}
