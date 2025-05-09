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
define( 'DB_NAME', 'desenvolvimento' );

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
define( 'AUTH_KEY',         '2Px-{72:?$O-80Mez{YytNhM_k%^f/;ll}=e7EN;A5)Pv9A>:Qt9XdE|6:^$9Bhe' );
define( 'SECURE_AUTH_KEY',  '[B!ugX)dOF>s6{v%Cj!PX6a5}1w~OWzp1P}M28l)QO{@lGAX37)szYJEE1bHZqR2' );
define( 'LOGGED_IN_KEY',    ')B>~MT8=``;_h1nr>~1I9qO-fX[e`N:FH&,den ^eiU.?aS[{uJI^d>.#b)LpHee' );
define( 'NONCE_KEY',        'g<P.<;KG43yyJ_92X,SsD]l~LMIHy6Fj!fZ_tb]0 rCVr-p,^>N?kO2!PFl6.eOE' );
define( 'AUTH_SALT',        'g,z0C~`r`.29$d6ue4u;b96%MUB}q*<|Zc$Y&}9kX8%WaTQ?#V#p6@Yj=sEvp?T<' );
define( 'SECURE_AUTH_SALT', ';Sa,6eFtJcayO{QJ0B,0S3grx_w,-bw~[%kLDQ~=G:(G&[3T2`6|2Uw/&%yq((=^' );
define( 'LOGGED_IN_SALT',   '{~1?9tS$THV=&iV^<3W<at0%.2f_k:j0|G*>:Lc|@aWPMAc@)h+-myEr;?V<PpAB' );
define( 'NONCE_SALT',       '|D~9t-wC#]_}%`g(B7Cw)B(~(hd0hZPOVZPH5^3a2*LH*dl$cKHV>=Ps6FA)oQrq' );

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
