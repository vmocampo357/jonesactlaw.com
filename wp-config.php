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
define('DB_NAME', 'maritimelaw_wp');

/** MySQL database username */
define('DB_USER', 'jonesact');

/** MySQL database password */
define('DB_PASSWORD', 'Joneslaw2017!');

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
define('AUTH_KEY',         'A?RT)U^+nr/,`G2FEg3y;TC ,WDA0->L-$SOvZ5E3e-%@ty=[oi)e5g!]#RSCXbs');
define('SECURE_AUTH_KEY',  '[[rm6cI U^e9lKzbH1O?h/0%z9R}-oLQ|P6-X&@=bNp0&4-4b=yvz`aPF-PY3<W%');
define('LOGGED_IN_KEY',    '-Z&?^K!bWRe0bEwm!$?1&}S^iy6N`Lw1e8qpHw}X2,^r>u`NrNBp$3pV406a+U.u');
define('NONCE_KEY',        'EM9@^)bptuBM:5rS]fG*fL*h!H:u=z^H%0*Lp;0_3S~=qAnn>Mr-vR{^(b&;f/Q=');
define('AUTH_SALT',        'M*/>&JNg)P10 =K^HGQ2sTi%;_e<VY$q+/dyz@@w)Gpjmp~TdcEcxv%S4><Buwan');
define('SECURE_AUTH_SALT', ':|%oEGmXqqmqQ=Th;#gV$C1m1@wf#{lDZ@fE*z4jhE%t5~rC7LHvT2KWS~{nTY<S');
define('LOGGED_IN_SALT',   'Z]S~`}(3T<%N=B9G*}}6lH[e4hN{dTE,Hotq3LC`vLW5elI9x`3xshBe;%t*3z|L');
define('NONCE_SALT',       'g[,uo?7,}v+jgGxL^v^&Ax:uC~#aj^:C#qU.5:>%uY0QcZFEt(jfvMj/bgg>#E=P');

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
define('WP_HTTP_BLOCK_EXTERNAL', false);
define('FORCE_SSL_ADMIN', false);
define(‘ALLOW_UNFILTERED_UPLOADS’, true);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
