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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'project3' );

/** MySQL database username */
define( 'DB_USER', 'sabbir' );

/** MySQL database password */
define( 'DB_PASSWORD', '1234' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'FXtlJK6(cqmU@qjU^$@Y77s,g^>$VJJ4BEll3Y2~xfjSntQd4k?P ((1% 7!F,t1' );
define( 'SECURE_AUTH_KEY',  '.@S<]![YI(x=Ha-k(PW$G6LT<glFvIXiK`9a@>u]]Ce;R? nFq8p_8`}PYT,GO$g' );
define( 'LOGGED_IN_KEY',    'sN#:DPI]{V9Z[znHL~s.fT~VUsr7E*2QJ+Nl.a`e*Gp$#>7rh/U6_L@%FIUfqdn)' );
define( 'NONCE_KEY',        '3a5RtD]An n&y$t)he1TNh+x~6}(h)ZjI= 4W%1KqMQ+d[7TfL@a/ANMy.fr:n66' );
define( 'AUTH_SALT',        'yt<k|t|2x[Dy(g;dd4QghWiDxWmh(pU}JfhALBz1Y(v[n2,zz(ymg7N!|R+j8<TO' );
define( 'SECURE_AUTH_SALT', ';&1x@fhqnPW}#*{%,Q$,pCH0<^AKJde&HUBs7:9Ji$=#9j&!?N,sX$B`:7RDLp|z' );
define( 'LOGGED_IN_SALT',   'yZcN$$F#/{PLvs@-YJ]cd894CAu!hB>2vz#!g3ir[K~cKt*G0MZGe)M&{*{8JhS&' );
define( 'NONCE_SALT',       'D]O0FsCkpNLFzjN>%Jd/;j[O!{K6fcrNcc^D$J[P/v?|SL`k*[}O9zHT =IIg^z$' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
