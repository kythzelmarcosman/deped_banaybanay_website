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
define('DB_NAME',     getenv('MYSQLDATABASE'));
define('DB_USER',     getenv('MYSQLUSER'));
define('DB_PASSWORD', getenv('MYSQLPASSWORD'));
define('DB_HOST',     getenv('MYSQLHOST') . ':' . getenv('MYSQLPORT'));
define('DB_CHARSET',  'utf8');

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
define( 'AUTH_KEY',         ':&Hp>XFcWEjwop{#w2FnVL<9FzL/1=H]lI$K2]tSEjjvARALyE!2y74)od,i @Il' );
define( 'SECURE_AUTH_KEY',  '-axSb< `gf9}fyeCY|}. am$XegojV:{m[A_d^Xa+n&^Vs[:1N)v0QJ]wPgBqs+G' );
define( 'LOGGED_IN_KEY',    'g_rmCo!OhbRjs!N2J?(wS1~oq-cVSPJsHH4rptsTG=]4rPhZaT4`Rc1?!zv}4p0O' );
define( 'NONCE_KEY',        'O+Wtfdw}AdA}%0nCp4&{_H,)3qf|i=O-TvN5*9$B9,|36Q8dH4F+j|*x8u]xm=lo' );
define( 'AUTH_SALT',        '^}6-47;d{,30eod%vG$vq-#6B8U =D2~pI9xu# kMD9qrv72G=?@HPv(gBU4bkON' );
define( 'SECURE_AUTH_SALT', 'f=nv5:uQ.Ui$0i4Y5_.LlblA3,Tc5NLY*!KLVXt$tKzYC};UF(G3Vvk!/0Dy0;XN' );
define( 'LOGGED_IN_SALT',   '5nHckm<U|.w(P1D^mNO{.XQk_un_.J0B*2`k7)qM(4zZZ=OVU|N~ZO@sX#x^#%kw' );
define( 'NONCE_SALT',       'WiYV aPAGJMEGk&>Ib93|N>M.E&@nI*L0&Ra/m8p#kG%*XPsn!e&*`<}mdxHL)Il' );

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
