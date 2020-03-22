<?php
/**
 * Load WordPress into the mix (for pushing Posts)
 * =====================================================================================================================
 */
// require_once('/var/www/dev.jonesactlaw.com/public_html/maritime/wp-load.php');
require_once('../../wp-load.php');



/**
 * Create an instance of PDO that we'll use to query for the Posts
 * TODO: Change this to PROD, READ-ONLY INFO once ready
 * =====================================================================================================================
 */
$host = '127.0.0.1';
$db   = 'devjonesactlaw_wp';
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
$posts = $pdo->query(
    "SELECT ID, post_date, post_content, post_title, post_excerpt, post_name, guid, menu_order FROM wp_posts WHERE post_type = 'faqs' AND post_status = 'publish'"
);
$posts = $posts->fetchAll();



/**
 * Prepare the statements we'll need for READING (Inserts will happen through WordPress)
 * =====================================================================================================================
 */
$get_metadata = $pdo->prepare(
    "SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = ?"
);
$get_categories = $pdo->prepare(
    "SELECT wp_terms.* FROM wp_term_relationships LEFT JOIN wp_terms ON wp_terms.term_id = wp_term_relationships.term_taxonomy_id WHERE object_id = ?"
);



/**
 * Loop through each Post now to get 3 different things:
 * - Metadata
 * - Categories
 * - Author Information
 * =====================================================================================================================
 */
$prepared_posts = [];
$prepared_categories = [];

foreach($posts as $post)
{

    # Create a new, prepared Post
    $prepared_post = $post;

    # Get all the meta-data -- we'll loop through this to create a Post
    $get_metadata->execute([$post['ID']]);
    $metadata = $get_metadata->fetchAll();
    foreach($metadata as $meta)
    {
        $prepared_post['meta'][$meta['meta_key']] = $meta['meta_value'];
    }

    # Get all categories
    $get_categories->execute([$post['ID']]);
    $categories = $get_categories->fetchAll();
    foreach($categories as $cat)
    {
        $prepared_categories[$cat['slug']] = $cat;
    }

    # Save the post's categories
    $prepared_post['categories'] = $categories;

    # Save the prepared post -- we'll come back to this
    $prepared_posts[] = $prepared_post;

}

pretty_print($prepared_posts[0]);