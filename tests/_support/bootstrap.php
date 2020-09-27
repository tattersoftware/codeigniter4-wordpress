<?php
		
// Bootstrap CodeIgniter
require_once HOMEPATH . 'vendor/codeigniter4/codeigniter4/system/Test/bootstrap.php';

// Locate the installation directory
defined('WORDPRESSPATH') || define('WORDPRESSPATH', HOMEPATH . 'build/wordpress/');

// Download the latest WordPress
$script = escapeshellcmd(HOMEPATH . 'vendor/bin/wp core download' .
	' --path=' . WORDPRESSPATH .
	' --skip-content' .
	' --force'
);
echo $script . PHP_EOL;
passthru($script);

// Create the config file
$script = escapeshellcmd(HOMEPATH . 'vendor/bin/wp config create' .
	' --path=' . WORDPRESSPATH .
	' --dbname=' . $_ENV['DB_NAME'] .
	' --dbuser=' . $_ENV['DB_USER'] .
	' --dbhost=' . $_ENV['DB_HOST'] .
	' --dbpass=' . escapeshellarg($_ENV['DB_PASS']) .
	' --force'
);
passthru($script);

// Install WordPress
$script = escapeshellcmd(HOMEPATH . 'vendor/bin/wp core install' .
	' --path=' . WORDPRESSPATH .
	' --url=example.com' .
	' --title=Example' .
	' --admin_user=supervisor' .
	' --admin_password=strongpassword' .
	' --admin_email=info@example.com' .
	' --skip-email'
);
echo $script . PHP_EOL;
passthru($script);
