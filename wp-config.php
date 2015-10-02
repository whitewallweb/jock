<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'jocklocal');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '1c3Cr3amMan.!');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'T4OtrVcnLI^Pv,+ xW+{@=hfK~BI[kd9+Wy],)tp+G )$zn;AkVETcv|GQv^3+-^');
define('SECURE_AUTH_KEY',  '*<lj~-1w>7eX[70J3ep>|-v5Fcg+ ;:?9<wxLJ{K4SbtdDGuryh-LWI!1G+ Z2HV');
define('LOGGED_IN_KEY',    'GU&nphrm+MSU+|*$ma;U>9Pc2HhtAsG`pfeQ`!|D^r&MIJLH7|^_@u26$q+0{`gr');
define('NONCE_KEY',        'e|o=+Le4!zp<qsEsG%5!]Z2S#Wx|Qe~*I)wzV6-bs/u&g+0mTROQM6$jj,w{[`u:');
define('AUTH_SALT',        '(g,V@--D=($q(^<Vl!gH<C~@h`xo{{6[,@zh@r7}nBT2r-6%+++,SG?WGnNe)J%|');
define('SECURE_AUTH_SALT', 'R%o-~|x--orq(R+$tUv)X?DezuP|+8n+&4OcYsc8(S9mgr[6$s$+#gBj0Yr<ceC-');
define('LOGGED_IN_SALT',   '|(6Q<|4a^+`6(y&B5gu|fa<UK;m/x,)<Q<Z-2m$|]h|0iM$8b_ f(VltIZDP(7hL');
define('NONCE_SALT',       '(3`<[*[6KO+-<J+8R$v9iYE|@Gb6GX=zvJmPt9|#BdUQZT||vEJ:L?Ep}V43|ugC');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

