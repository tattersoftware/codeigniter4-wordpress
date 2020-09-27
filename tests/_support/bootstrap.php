<?php

define('ABSPATH', HOMEPATH . 'vendor/johnpbloch/wordpress-core/');

// Database configuration
define('DB_NAME', 'ci4_tests');
define('DB_USER', 'ci4_tests');
define('DB_PASSWORD', 'aGlradmIzF@SGkW7n8UYCvEsAeADUaEk');
define('DB_HOST', 'tatter.citsvlxipct1.us-east-1.rds.amazonaws.com');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

require_once ABSPATH . 'wp-includes' . DIRECTORY_SEPARATOR . 'wp-db.php';

$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

require_once ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'wp-includes' . DIRECTORY_SEPARATOR . 'schema.php';

$result = wp_get_db_schema();

var_dump($result);
