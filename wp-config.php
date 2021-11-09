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
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'website_orbit' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
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
define( 'AUTH_KEY',         '.s7aXZy6#kQIIuHMRAn#M;wD1O:i593a%zD({DAAta.[qI dl-Qkk<{Q[/Q^AO%W' );
define( 'SECURE_AUTH_KEY',  'W<MSxBp:4Ofyv%7gas(t)w@|LNH!1gbedsqOnH>zram47rvVcesv%lmQ #Qd>4/e' );
define( 'LOGGED_IN_KEY',    '`=d[{mxgpAi*0_6W%:idQe1L>f&6xLk`P9nlAf:KuD|at(-j M,I+Fxl`UwunJmh' );
define( 'NONCE_KEY',        'BrWmy9u.cMtS:!Ew}MV?^.Ev>j4FPd]k-Ds5Qdx4fMGt*G8EI%mS%+U?ZrM}-#{g' );
define( 'AUTH_SALT',        'unh6-=X9@]<I9RG*VRrAdMj=^>G;27p,F]@RCRyGDe`$vPDL`]xuUC;V_+IPnl9F' );
define( 'SECURE_AUTH_SALT', 'YB}wEob|{p~asna7;=V@V|X$OL^qSz^7r_sQN@mz#v:Ezo&h6i-7vlZ@Y?!ThQ>n' );
define( 'LOGGED_IN_SALT',   'S6~n2fWb`.ilzXU9d`VmH&5q@Jb*%%qm ,3RY@jV)MT,s`v} 1wbw4cKqp!xCNs/' );
define( 'NONCE_SALT',       '6n<mR^_<[O_-w%v_ZeH+IYyr1<.y{9r2kzzjyQHCo1>/Dr*MT?yX>x/ l)Il`tU{' );

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
