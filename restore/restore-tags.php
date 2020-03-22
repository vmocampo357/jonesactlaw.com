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

# OK, let's first start by creating INSERT scripts for all the tags to restore them
$tags = $pdo->query("SELECT * FROM wp_terms A LEFT JOIN wp_term_taxonomy B ON B.term_id = A.term_id WHERE B.taxonomy = 'category'");

echo json_encode($tags->fetchAll());
