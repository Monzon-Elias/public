<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
if(strstr($_SERVER['SERVER_NAME'], 'university-x.local')) {
	/** The name of the database for WordPress */
	define( 'DB_NAME', 'local' );

	/** MySQL database username */
	define( 'DB_USER', 'root' );

	/** MySQL database password */
	define( 'DB_PASSWORD', 'root' );

	/** MySQL hostname */
	define( 'DB_HOST', 'localhost' );
} else {
	/** The name of the database for WordPress */
	define( 'DB_NAME', 'idahazgx_university-x' );

	/** MySQL database username */
	define( 'DB_USER', 'idahazgx_elios' );

	/** MySQL database password */
	define( 'DB_PASSWORD', '!Ph2qrq,@c58' );

	/** MySQL hostname */
	define( 'DB_HOST', 'localhost' );
}


/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'hjmJ2rJEJguZvP+0MdTJBxdLZ+RBGS6D+RsciPCLKGys/kYTC3dgQXSeXnBDq/w0+tUeNejVPlaE7A036V8ZiA==');
define('SECURE_AUTH_KEY',  'NQ+T75xx77TvL3+x6TnHaWQ+4Be/QVTapTcVzizoMYvP1zxd78gfrqlWoSmdSBtM2fhPesrp6tnD4W/bNW8CAg==');
define('LOGGED_IN_KEY',    'MnqZp2u/XJH4j3nzRFnPhcYjk/3rq9VRsUwvDjGROK6mFhM1QY8WsSCtegnlenxs5Fl2jElh58gnY8q9Zjxujw==');
define('NONCE_KEY',        'awn3BSvtxmsXRKKRCRzb84LhvtyMYv9e1F8TF7XZdtS1DmNcPT0IXMaeI2/QV0DqE990r1M57gpBQaCLM1rKUg==');
define('AUTH_SALT',        'Qw9VOkaQq/NP2V/Pt6ypcCFA+4xWvVjXm8avOO25g4d2wESVSibs2v+dAYcRDFZ5iijq2RaW1E/7UxsMwttgYA==');
define('SECURE_AUTH_SALT', 'yLs5fl80WaEOmXcKibff9OltCjqUjtN7a5EnausS+ZPy7kLrzg9UvINbWTq8FHCQQbOQtFw0S2/1wPrXnpCJ/w==');
define('LOGGED_IN_SALT',   '9rq2jtcHHIeyywPZpbWVWdxruBhJbFh84gkXNhgFTs30HJpe3BkayXv/Cm0Y8Pr2JsiKkZ0TjCdFyV7Jsf8+Hw==');
define('NONCE_SALT',       'oQ5xpV3dM+hg1CjhUsWLjz1PBRxYs9e89S4bLtwwR2kviLoblZddTofm0Ev+vH0VzjzGiy/PcoeA6IpQDnEsFg==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';