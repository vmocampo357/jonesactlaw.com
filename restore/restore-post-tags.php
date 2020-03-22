<?php
header('Content-type: application/json');

/**
 * Load WordPress into the mix (for pushing Posts)
 * =====================================================================================================================
 */
// require_once('/var/www/dev.jonesactlaw.com/public_html/maritime/wp-load.php');
// require_once('../wp-load.php');



/**
 * Create an instance of PDO that we'll use to query for the Posts
 * TODO: Change this to PROD, READ-ONLY INFO once ready
 * =====================================================================================================================
 */
// $db   = 'jonesactlaw_wp';
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

# Start by grabbing all the 'posts' that have term relationships with a tag
$tags = $pdo->query("
    SELECT 
      C.post_title, D.slug
    FROM
      wp_term_relationships A
    LEFT JOIN
      wp_term_taxonomy B
    ON 
      B.term_taxonomy_id = A.term_taxonomy_id
    LEFT JOIN
      wp_posts C
    ON 
      C.ID = A.object_id
    LEFT JOIN
      wp_terms D
    ON
      D.term_id = B.term_id 
    WHERE B.taxonomy = 'category' AND D.slug != 'uncategorized' ORDER BY C.post_title DESC");

// pretty_print($tags->fetchAll());

echo json_encode($tags->fetchAll());
