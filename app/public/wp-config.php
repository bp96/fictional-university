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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         'bLT2iwlpn/193BQ0HDwxGqiwUSI4Kt44JA6PdYOnWJWApsMqU+bedXmhQNjdbVST1eLZn7TorSQlieKTqRSLYw==');
define('SECURE_AUTH_KEY',  'fYY5YO81ZZO42lGtiUlKSClk8fPlp0N1L+97jrRxRFFerR/l5z1Dsn3cxRScYIQgWRw3piG5Ydi2Vg19vcxUrA==');
define('LOGGED_IN_KEY',    'sAG6Z0V98My25hMb0VyLnPjB9gh73Mi5ucG9MIebnoiyTBeO1Phmp06BXgwPUUtuvuh6KZc7svnpU7adhbRcvg==');
define('NONCE_KEY',        'e5bx5Rrz+MsMzKzBSHXdJ63JmGRXGohQsHU94LfW6BTtR2/k3hdY0z9TO2MrUDitaqo3mIarK7ocxNeYK74z2g==');
define('AUTH_SALT',        'CpzpY5CqnHVndf86Z64GfDTkAaOWOL2JDoYBENIr44U9t9z0FYEHgJ1etZwSZtK50YIfSN61sbOCOEuXovjeUA==');
define('SECURE_AUTH_SALT', 'QEf+SGiOYKPP+jWHAgWGS4N5kwf5abIkJkxHd7/MFGMEmFx8huMPaS3aqCu2XU+Dx48K7Qgf6kCQkvmapISzhA==');
define('LOGGED_IN_SALT',   'EAddUkjQpeGphRf/tRIKsPoIUxwooDlIs5Ae49+kdI+BDyaXY7cBGqxxugLJskYfdT5MBW+T3AdxucGHBcf4vA==');
define('NONCE_SALT',       'NKKJRxnbXslTXp2Llzio2Lg+ux2Wlhp7KtwPi54aOcPI0lduhOei/NvoF3EqnQRh58Q0uw3qGninCav0MNHasA==');

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
