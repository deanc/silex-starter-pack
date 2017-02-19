<?php

// toggle for debug mode
define('DEBUG_MODE', true);

// dbal info
define('DB_DRIVER', 'pdo_mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_FORCE_UTF8', false); // forces the connection string to use utf8. never use in production

// SET THIS TO A VERY LONG AND UNIQUE + RANDOM KEY
define('ADMIN_UNIQUE_RANDOM_KEY', '');

// user settings
define('USER_USERNAME_MIN_LENGTH', 5);
define('USER_USERNAME_MAX_LENGTH', 16);
define('USER_PASSWORD_MIN_LENGTH', 12);
define('USER_ENABLE_PROFILE_PAGES', true);

// bonus goodies
define('TWILIO_ENABLED', false);
define('TWILIO_ACCOUNT_SID', '');
define('TWILIO_ACCOUNT_TOKEN', '');