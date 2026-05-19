<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress_local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '3a_Y5KD@9Oh,<,t][rg3me.XP#2Cn!2a},cQSX:CKHliaj&R.7=beX4NVGC}}5Qn' );
define( 'SECURE_AUTH_KEY',  ']yc_+hU%t/SwOQ{9k[!tF6_#xlt,@>~2eWIJOu}RP;]lzlkhYO{%|^,zT)R/-WOy' );
define( 'LOGGED_IN_KEY',    '`g>*z}sm[}}Keg&DZeJZRdEZns<RHL!vFv6JOj@2EcXPN)^B9XB{j{}6f;hj`2em' );
define( 'NONCE_KEY',        '4,-IT?2$iqq/t$o0RvUU>IwDp>StULy]]#gv3zH@O=Zvq?_ofUFb4+$ai1d=B6Sq' );
define( 'AUTH_SALT',        '0PIfz2oRX9dp;poG14AgFhZmdbCf6N;OA!Pi-FgyU46P. nXpcmSleW3y/qThaJl' );
define( 'SECURE_AUTH_SALT', '&VUw>_},(M;2j_vs%LK,hm[,7Znw.OIPhku8V?0e=zg]=2? _`el2I{A]D2K2a>U' );
define( 'LOGGED_IN_SALT',   '^0L.~DH%p:3Dq~ u=[hh)E()TTPc/6[Z[]~- 4t&>O1?<[E!etTG*{a(f*Bjpw9(' );
define( 'NONCE_SALT',       'fJjD[^yJ*p;oY6a M,Bpv>C|_-kjFr{jS/q0yR8)0G~S&%y!JHakz-Nag&s wqF/' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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
