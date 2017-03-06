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
/** The name of the database for WordPress */
define('DB_NAME', 'comparlo_wpwebsite');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '1234');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '`;t#j`!#c6/jnilb^yrLe23?7zMT=^hyfgL7v%09;PS>fUMew;Tpsfa&evjqPZ@.');
define('SECURE_AUTH_KEY',  'M6-vS<-@MV7=!A/]XC32%5pCA +PW2Cv8`C`bIp{GCNe7pPl{E;G7y:PrX}V[CeI');
define('LOGGED_IN_KEY',    'xv^x.q&kjF.nz8z/FB2PS@;@0`l_UvO[GdMG3G~D3v?(DXOm9=W56*1%y:Sf~F-A');
define('NONCE_KEY',        '[ 9a]?h3^Uz|oR tlOqp{K>!,{gGFyuYJp7 -eF(CXy`tNl_N98-QEsKuop0[j;z');
define('AUTH_SALT',        'gJ?,FwoU*km9Kdk!W]->Z  EEn[<mG6[Nx TyFoko,/)stzvU t}C>PHwE QXbo<');
define('SECURE_AUTH_SALT', '3^_tHy/>$ i8t>?t_9~N%9?AKXq9T.QWCrw0IP:U=wS_XP71n3p/^+|@LT(h,6jC');
define('LOGGED_IN_SALT',   '8{.5;jm+8Nz@r.w=jRbLe?9(FL?{L}|>1(FbL?C2)usm/}b!,=NR8YIg7*f]Qb:P');
define('NONCE_SALT',       'mZp`jmPQ*{YQXlm7Zk[UPp~Rxa{hB,Ors<_1.~u^XrQ^zLoh!gZy^SF^m1`28Jez');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
