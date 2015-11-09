<?php

// dbal info
define('DB_DRIVER', 'pdo_mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_FORCE_UTF8', false); // forces the connection string to use utf8. never use in production

// security
define('FORCE_HTTPS', false);

// user + pass for the admin login
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg=='); // foo

// Set this to a long and random key
define('ADMIN_UNIQUE_RANDOM_KEY', '');

// toggle for debug mode
define('DEBUG_MODE', true);
