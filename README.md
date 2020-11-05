# Tatter\WordPress
WordPress content management for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-wordpress/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-wordpress/actions?query=workflow%3A%22PHPUnit)
[![](https://github.com/tattersoftware/codeigniter4-wordpress/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-wordpress/actions?query=workflow%3A%22PHPStan)

## Quick Start

1. Install with Composer: `> composer require tatter/wordpress`
2. Add a new database connection:
```
	public $wordpress = [
		'DBDriver' => 'Tatter\WordPress\Database',
		'WPConfig' => '/path/to/wp-config.php',
	];
```

## Description

**Tatter\WordPress** provides a way for you to connect your CodeIgniter 4 instance to an
existing WordPress installation.

## Usage

This library comes with the `Reader` class, a parser designed to read configuration values
from WordPress' **wp-config.php** file. By extracting database information and installation
path, `Tatter\WordPress` can connect to the same database and modify information using the
supplied models.

## Database

In order to use the database you need to define a new database group that uses the
connection details provided by `Reader`. Add a property to **app/Config/Database.php**
with the driver and the path to your **wp-config.php** file, like this:

```
class Database extends BaseConfig
{
	public $wordpress = [
		'DBDriver' => 'Tatter\WordPress\Database',
		'WPConfig' => '/path/to/wp-config.php',
	];
```

## Models and Entities

This library defines Models and Entities that correspond to WordPress's database tables.
You may use them like ordinary CodeIgniter 4 Models, but pay attention to WordPress's
[particular database structure](https://codex.wordpress.org/Database_Description). "Meta"
tables are handled via a special Entity extension `MetaHandler`, which allows read/write
access to individual meta rows as class properties:
```
// Get a particular Post
$post = model('Tatter\WordPress\Models\PostModel')->find($postId);

// Access post metadata
echo $post->meta->_wp_page_template; // 'default'

// Update post metadata
$post->meta->_wp_page_template = 'mobile';
```

## Commands

There are a few commands to make it easier to interact with your configuration - these are
also a great way to make sure your WordPress database is set up correctly.

* `posts:list` - Lists all Posts in a table format
* `posts:show [postId]` - Displays details for a single Post
* `posts:delete [postId]...` - Deletes one or more Posts by their ID
