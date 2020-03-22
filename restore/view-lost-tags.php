<?php
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
$db   = 'jonesactlaw_wp';
$host = '127.0.0.1';
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


$tags = [];
$fh = fopen('lost-tags.txt', 'r');
while (($data = fgetcsv($fh, 1000, "\n")) !== FALSE) {
    $tags[] = $data[0];
}
fclose($fh);



$find_tag = $pdo->prepare("SELECT * FROM wp_terms WHERE name = ?");



$insert_tag = $pdo->prepare("INSERT INTO wp_terms (name,slug) VALUES (:name,:slug)");
$insert_tax_record = $pdo->prepare("INSERT INTO wp_term_taxonomy (term_id, taxonomy) VALUES (:term_id, :taxonomy)");



function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}




foreach($tags as $t)
{
    $find_tag->execute([$t]);
    $results = $find_tag->fetchAll();
    if(empty($results)){

        $slug = slugify($t);

        $result_a = $insert_tag->execute([
            'name' => $t,
            'slug' => $slug
        ]);
        if($result_a){
            $result_b = $insert_tax_record->execute([
                'term_id' => $pdo->lastInsertId(),
                'taxonomy' => 'post_tag'
            ]);
            if(!$result_b){
                echo "Could not insert TAG: " . $tag['name'] . "<br />";
            }
        }else{
            echo "Could not insert TAG: " . $tag['name'] . "<br />";
        }
    }
}