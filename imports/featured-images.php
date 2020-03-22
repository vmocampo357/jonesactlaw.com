<?php
/**
 * Load WordPress into the mix (for pushing Posts)
 * =====================================================================================================================
 */
// require_once('/var/www/dev.jonesactlaw.com/public_html/maritime/wp-load.php');
require_once('../wp-load.php');
require_once('../wp-admin/includes/image.php');
require_once('../wp-admin/includes/media.php');

/**
 * Instance of PDO for the PRODUCTION database
 * TODO: Change this to PROD, READ-ONLY INFO once ready
 * =====================================================================================================================
 */
$host = '127.0.0.1';
$db   = 'jonesactlaw_wp';
$user = 'jonesact';
$pass = 'Joneslaw2017!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$prod_pdo = new PDO($dsn, $user, $pass, $opt);

/**
 * Instance of PDO for the MARITIME database
 * TODO: Change this to PROD, READ-ONLY INFO once ready
 * =====================================================================================================================
 */
$host = '127.0.0.1';
$db   = 'maritimelaw_wp';
$user = 'jonesact';
$pass = 'Joneslaw2017!';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$dev_pdo = new PDO($dsn, $user, $pass, $opt);

/**
 * First thing we want to do is find all the POST IDs on production that DO have a featured image,
 * we can do this by looking up some information on the meta-data table for each post,
 * where the featured image information is found.
 *
 * Then, we want to find out if we have the post on our side as well, and then so forth.
 *
 * We'll have to go by titles. Eek
 */
$posts_and_thumbs_sql = "SELECT M.post_id, M.meta_value, P.ID, P.post_title, P.post_type, A.post_title AS thumb_img FROM wp_postmeta M INNER JOIN wp_posts P ON P.ID = M.post_id INNER JOIN wp_posts A ON A.ID = M.meta_value WHERE (meta_key = '_thumbnail_id' && meta_value != \"\") AND P.post_type NOT IN ('product','attorneys') ORDER BY P.post_title ASC";
$posts_and_thumbs = $prod_pdo->prepare($posts_and_thumbs_sql);
$posts_and_thumbs->execute();

while( $row = $posts_and_thumbs->fetch(PDO::FETCH_ASSOC) )
{
    echo "<pre>".print_r($row,true)."</pre>";
}