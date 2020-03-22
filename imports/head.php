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
 * Create an instance of PDO that we'll use to query for the Posts
 * TODO: Change this to PROD, READ-ONLY INFO once ready
 * =====================================================================================================================
 */
   $host = '127.0.0.1';
// $db   = 'devjonesactlaw_wp';
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
$pdo = new PDO($dsn, $user, $pass, $opt);



/**
 * Set-up any functions we need
 * =====================================================================================================================
 */

/**
 * @param $mixed
 * @return void
 */
function pretty_print($mixed)
{
    echo "<pre>".print_r($mixed,true)."</pre>";
}



/**
 * Start by grabbing all the Posts we can
 * =====================================================================================================================
 */
$posts = $pdo->prepare(
    "SELECT ID, post_date, post_status, post_content, post_title, post_excerpt, post_name, guid, post_mime_type, menu_order FROM wp_posts WHERE post_type = ?"
);
$posts->execute([$post_type]);
$posts = $posts->fetchAll();



/**
 * Prepare the statements we'll need for READING (Inserts will happen through WordPress)
 * =====================================================================================================================
 */
$get_metadata = $pdo->prepare(
    "SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = ?"
);
$get_categories = $pdo->prepare(
    "SELECT TR.object_id, terms.*, TT.taxonomy FROM wp_term_relationships TR LEFT JOIN wp_term_taxonomy TT ON TR.term_taxonomy_id = TT.term_taxonomy_id LEFT JOIN wp_terms terms ON terms.term_id = TT.term_id
      WHERE TR.object_id = ?"
);


