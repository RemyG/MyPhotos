<?php 

date_default_timezone_set('Europe/London');

//define('BASE_URL', 'http://localhost/photos/'); // Base URL including trailing slash (e.g. http://localhost/)
define('BASE_URL', 'http://photos.local/'); // Base URL including trailing slash (e.g. http://localhost/)

define('DEFAULT_CONTROLLER', 'main'); // Default controller to load
define('ERROR_CONTROLLER', 'error'); // Controller used for errors (e.g. 404, 500 etc)

/*
define('DB_DSN', 'mysql:dbname=gallery;host=localhost'); // Database DSN
define('DB_USERNAME', 'root'); // Database username
define('DB_PASSWORD', ''); // Database password
*/

define('DB_DSN', 'mysql:dbname=gallery;host=localhost'); // Database DSN
define('DB_USERNAME', 'root'); // Database username
define('DB_PASSWORD', ''); // Database password

define('DB_TABLE_PREFIX', 'gal_');

define('PICTURE_PATH',			'images/');
define('COVER_DIR',				'cover/');
define('THUMB_DIR',				'thumb/');

define('COVER_WIDTH',			400);
define('COVER_HEIGHT',			400);

define('THUMB_WIDTH',			300);
define('THUMB_HEIGHT',			300);


?>