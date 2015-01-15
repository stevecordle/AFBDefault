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
    
    define('DB_NAME', 'jroy');
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
    
    define('DB_NAME', 'prod_dbname');
    define('DB_USER', 'prod_user');
    define('DB_PASSWORD', 'prod_pass');
    define('DB_HOST', 'localhost');
} 


define('AUTH_KEY',         'E|S@P6wertwertwerrib|IB h#D*sdfasdfasdfasdfasdsddN{$2I}|xwretCq=');
define('SECURE_AUTH_KEY',  'Zg=hV)Ri=_T|Z|]S5Ddraasno9fy3y9bfyfov3y9fvoif4gh|8SNT[d+>uEpkD9Q');
define('LOGGED_IN_KEY',    '^(u Hc}TKsdgdgergergerertwertr{;[jFag7?N[|)c#R#-UFV)!B%+M]Lw)4Z&');
define('NONCE_KEY',        '1~ps531g^P)-Ce$Iq-YgH<sdfhererherhafhdbte5a5a3443bvIH6s,,&9>4@lK');
define('AUTH_SALT',        '3{?U~4G+vtgi93jgnc383v893483983rgh34j}T pFjT}a^T9+-8J|[8~^s>CASC');
define('SECURE_AUTH_SALT', 'J!*Ym7n**V%*&^V&^$&%OVIIGYB*BH()H(B*YO*&^BI^BVR^*&RB^(*T^*BwG_@X');
define('LOGGED_IN_SALT',   'lUY*r%_pk?NqVV^%I&&$^C%$^IC^KY%IVVYYFrtwertR*DN!)8v*DaL7#K:^?H]=');
define('NONCE_SALT',       'ULNiep=-wertIV&%i7465cvklivKRkuUBNI6r#>/.zIE@#;tBq?6$F,7o7<[=TtM');

$table_prefix  = 'wp_';


/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

require_once('vendor/autoload.php');
