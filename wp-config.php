<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'i6889255_wp3' );

/** Database username */
define( 'DB_USER', 'i6889255_wp3' );

/** Database password */
define( 'DB_PASSWORD', 'S.fq73WM9I43Wg0OFYP20' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '2maLWg4pyyFBhFZYwtzmEIIGaLwZZsReQwaMaVFd6j12qBycWoBNbtjAGasAyUcu');
define('SECURE_AUTH_KEY',  'QfX98z4UfThVD2clkql1iMM6dHGjsPXTIVDdsnSo6u8xFW2KgKLQomC44k15MXlP');
define('LOGGED_IN_KEY',    'mN8zzOZUBenHG6xtFzlQP4u7qRXwukLpnXbeXqUzAKmSaC78p8V6ZzzbwT7aRzo3');
define('NONCE_KEY',        'WTHgZYEYVYHSDEg6AoPMQnis84SzAkZDQJwGM8r51tEbYOWZ3j1rMKbqFZHGxtGD');
define('AUTH_SALT',        'DLx7b1D3tuNk6trCWS1VKnWAqHyDrsVzSfx0HpuCZ8L9pa0lXxazsyfJzrVKt12r');
define('SECURE_AUTH_SALT', 'WFdXxJXgF3UsFa7ty51h2gn4iSR3SSeO7sOSZoDBbqSK0XoUvjBVC2DMmQLZeakF');
define('LOGGED_IN_SALT',   'aoYDuuAo52CeE7YRlW1yysJwVPEwWc2hLYi5RzQED4diRehjwuoDrtWFRjPIdflY');
define('NONCE_SALT',       'qPmhwE6OJUm6Wer8H2NYWIslG0oKG6gc47iJGiSDI3mL15UYO6mZepu72TPaVsCr');

/**
 * Other customizations.
 */
define('FS_METHOD','direct');
define('FS_CHMOD_DIR',0755);
define('FS_CHMOD_FILE',0644);
define('WP_TEMP_DIR',dirname(__FILE__).'/wp-content/uploads');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
