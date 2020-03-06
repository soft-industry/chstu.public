<?php

require_once(__DIR__.'/env/env.php');

define('DB_CHARSET', 'utf8mb4');
define('AUTOSAVE_INTERVAL', 10000); 
define('WP_POST_REVISIONS', 3 );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'I(jUmB;cZ+v2aki%ydy~Re9[#xpA. fvWS18``qB#HU1MAM[qY*:uD#p-o$jDJj+' );
define( 'SECURE_AUTH_KEY',  '8d)en4[C2f<jYbRV#_(Jd**Z#,o.&[2s{?UIOrUp91`n2L!SaH<0<RUt5=wd-]F4' );
define( 'LOGGED_IN_KEY',    '4JR44pw.tz#sQFd1u2e>(YE.WPIdHV9{JD*>&4hF>VP*mv,yj<^(^+&iS/bv|xtS' );
define( 'NONCE_KEY',        'bvV2_]oTFlUvMxbZ%L.Y(nM$v-5cL_l-G!s*~n&?0dwCkF]@C2+t[gs@H+PUn=DE' );
define( 'AUTH_SALT',        '(~%@Dh?.75;D53}G4jV_k]K[(,k3}{4GXj<kNF!YI)fTN;(=SIzcVp)X(K1P}8, ' );
define( 'SECURE_AUTH_SALT', '3HVi]o23A%^Jd)H3g`=U5bI5FzsVkGd:m}.3mG8t50xNQA|>38gJ]0cJA;%wh1wN' );
define( 'LOGGED_IN_SALT',   'VIKzNLy}nuf.I]E+7v}xG2C,N-6JG^x:P&L/OQt{jma^3/l>z/,i*;z$WQ+(#x`)' );
define( 'NONCE_SALT',       'd}8|2j-|hiAjNMEu98=]l%:=w`vW]O(:yKdpP?1AQmkb}R~EqvC& UGWSc!=qVe}' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
