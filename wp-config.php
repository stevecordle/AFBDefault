<?php

if ($_SERVER['REMOTE_ADDR']=='127.0.0.1') {
    define('WP_ENV', 'dev');
} else {
    define('WP_ENV', 'prod');
}

if (WP_ENV == 'dev') {
    define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/wordpress');
    define('WP_HOME',    'http://' . $_SERVER['SERVER_NAME']);
    define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/wp-content');
    define('WP_CONTENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content');

    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', true);
    define('WP_DEBUG_DISPLAY', true);    
    define('SCRIPT_DEBUG', true);
    define('SAVEQUERIES', true);
    
    define('DB_NAME', 'taproom');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_HOST', 'localhost');
} else {
    define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/wordpress');
    define('WP_HOME',    'http://' . $_SERVER['SERVER_NAME']);
    define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/wp-content');
    define('WP_CONTENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content');

    define('WP_DEBUG', false);
    define('WP_DEBUG_LOG', false);
    define('WP_DEBUG_DISPLAY', false);    
    define('SCRIPT_DEBUG', false);
    define('SAVEQUERIES', false);
    
    define('DB_NAME', 'r_tap_room');
    define('DB_USER', 'r_tap_room');
    define('DB_PASSWORD', 'Taproom2015#');
    define('DB_HOST', 'localhost');
} 

/*********************************************************************************************
 *  VISIT:    https://api.wordpress.org/secret-key/1.1/salt/                                 *
 *  To copy/paste keys below                                                                 *
 *********************************************************************************************/

define('AUTH_KEY',         '-nZ6kj>[OI.jsw1:`qSN;-ZbdWUng]ro|d-0B<ZW8#<i~<Kah.y[@1A{6N(%[/H$');
define('SECURE_AUTH_KEY',  '|0[8_rVvu<I>zVJJO}E)PsC([2RkOiRA&ww&P78l6F@ L[=X6j! s-nrNM7wU+`C');
define('LOGGED_IN_KEY',    '#HJN6Iv?tK>&K${z!8N35+uqI!bzguE2Zd|-`-d]Qee`.F,6#IhI}$^izvV+:2?d');
define('NONCE_KEY',        'Pw+Tbeu+DHqa@mY{E[:Gx&^l :/q(n{]B`D{?,j{p#kI2zS-$t|IO|y+|/0m?vya');
define('AUTH_SALT',        ':Wi,#uH3s* GAnReJI*;B47+<Xb0g+,R]NT%O?MJ(;X82~I7%#)aY31qr44hJxlG');
define('SECURE_AUTH_SALT', 'pyB!.QKyxc -I36TQ G<.-#j+EYI#~CNpj-=7Qd0-/f$rK*ybkRj^s$d=)+.+;1+');
define('LOGGED_IN_SALT',   'nPY}Z ]ZAU+<x_CgA;]F|S> ,_:ck OBgL7B4%p;QCtd]R-lP7=-|3>+-U/U47Ui');
define('NONCE_SALT',       '+exs=xynws%FNtW-#q4,)OQiTFpV^vkDr.u<L+Yc!4>|uSBP|wI6Sg=a|H[0IB8h');

$table_prefix  = 'wp_';

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
require_once('vendor/autoload.php');