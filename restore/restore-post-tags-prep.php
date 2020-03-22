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

/*
*     [4] => stdClass Object
(
[post_title] => Woman Receives $125,000 Settlement for Cruise Ship Injury
[slug] => settlement
)

 */
$get_post = $pdo->prepare('SELECT ID FROM wp_posts WHERE post_title = ? LIMIT 1');
$get_term = $pdo->prepare('SELECT A.term_taxonomy_id FROM wp_term_taxonomy A LEFT JOIN wp_terms B ON B.term_id = A.term_id WHERE (B.slug = ? AND A.taxonomy = \'post_tag\') LIMIT 1');
$insert = $pdo->prepare('INSERT INTO wp_term_relationships (object_id, term_taxonomy_id) VALUES (?,?)');

$json_relationships = "[{\"post_title\":\"Your Jones Act Claim is Unique and So Is Its Value\",\"slug\":\"value\"},{\"post_title\":\"Your Jones Act Claim is Unique and So Is Its Value\",\"slug\":\"jones-act-claim\"},{\"post_title\":\"Work Injury Surgery Recovery: Six Things to Do\",\"slug\":\"tips\"},{\"post_title\":\"Woman Receives $125,000 Settlement for Cruise Ship Injury\",\"slug\":\"cruise\"},{\"post_title\":\"Woman Receives $125,000 Settlement for Cruise Ship Injury\",\"slug\":\"settlement\"},{\"post_title\":\"Will you owe taxes on your Jones Act settlement?\",\"slug\":\"taxes\"},{\"post_title\":\"Who is responsible for the Deepwater Horizon explosion?\",\"slug\":\"bp\"},{\"post_title\":\"Who is responsible for the Deepwater Horizon explosion?\",\"slug\":\"explosion\"},{\"post_title\":\"Who is responsible for the Deepwater Horizon explosion?\",\"slug\":\"liability\"},{\"post_title\":\"When a Back Injury Claim under the Jones Act Involves Injury to the Spine\",\"slug\":\"back-injuries\"},{\"post_title\":\"What was the Jones Act law for lost wages after maritime accidents?\",\"slug\":\"lost-wages\"},{\"post_title\":\"What to Do if Your Employer Offers You Advances When Pursuing a Jones Act Claim\",\"slug\":\"employer\"},{\"post_title\":\"What to Do if Your Employer Offers You Advances When Pursuing a Jones Act Claim\",\"slug\":\"advances\"},{\"post_title\":\"What test can show work related hearing loss\",\"slug\":\"hearing-loss\"},{\"post_title\":\"What is the Jones Act and How Does it Help You\",\"slug\":\"claim-process\"},{\"post_title\":\"Waiting for a check? Maritime law protects a seaman\u2019s wages.\",\"slug\":\"wages\"},{\"post_title\":\"Tugboat worker crushed by mooring lines\",\"slug\":\"tugboat\"},{\"post_title\":\"Tugboat Sinks on the Seattle Waterfront, Spilling Oil\",\"slug\":\"tugboat\"},{\"post_title\":\"Transocean to pay $4M to Worker Injured in Offshore Oil Rig Accident\",\"slug\":\"oil-rig\"},{\"post_title\":\"Transocean to pay $4M to Worker Injured in Offshore Oil Rig Accident\",\"slug\":\"transocean\"},{\"post_title\":\"Training Requirements for Polar Code Approved by IMO\",\"slug\":\"training\"},{\"post_title\":\"Training Requirements for Polar Code Approved by IMO\",\"slug\":\"regulations\"},{\"post_title\":\"Training Requirements for Polar Code Approved by IMO\",\"slug\":\"imo\"},{\"post_title\":\"Tips for Coping with Injury after an Offshore Mississippi Maritime Accident\",\"slug\":\"tips\"},{\"post_title\":\"The Top Fifteen Causes of Shipyard Crane Accidents: Who Is Responsible For Your Injury\",\"slug\":\"shipyard\"},{\"post_title\":\"The Pros and Cons of an Offshore Oil Rig Job\",\"slug\":\"offshore\"},{\"post_title\":\"The Jones Act and Rising Gas Prices\",\"slug\":\"gas\"},{\"post_title\":\"The Jones Act and Increased Oil Production\",\"slug\":\"oil\"},{\"post_title\":\"Texas Barge Owner Cited in Deaths of Two Workers\",\"slug\":\"barge\"},{\"post_title\":\"Texas Barge Owner Cited in Deaths of Two Workers\",\"slug\":\"death\"},{\"post_title\":\"Speed and human error caused Houma barge crash\",\"slug\":\"barge\"},{\"post_title\":\"Slip and Fall Boat Accidents: When the Pain Comes Later\",\"slug\":\"slip-and-fall\"},{\"post_title\":\"Search for Missing Crewman off Louisiana Coast Suspended\",\"slug\":\"missing-crew\"},{\"post_title\":\"Search for Missing Crewman off Louisiana Coast Suspended\",\"slug\":\"louisiana\"},{\"post_title\":\"Seaman Sues Employer for On-the-job Shoulder Injury\",\"slug\":\"shoulder-injury\"},{\"post_title\":\"Seaman Sues after Injuring Fingers on Anchor Rope\",\"slug\":\"vessel\"},{\"post_title\":\"Seaman Sues after Injuring Fingers on Anchor Rope\",\"slug\":\"ropes\"},{\"post_title\":\"Seaman Injured After Falling Through Hatch\",\"slug\":\"slip-and-fall\"},{\"post_title\":\"Seaman Injured After Falling Through Hatch\",\"slug\":\"vessel\"},{\"post_title\":\"Seaman Injured After Falling Through Hatch\",\"slug\":\"tankerman\"},{\"post_title\":\"Safety Tips for Working on a Boat and How the Jones Act Affects You\",\"slug\":\"safety\"},{\"post_title\":\"Safety Tips for Working on a Boat and How the Jones Act Affects You\",\"slug\":\"tips\"},{\"post_title\":\"Safety Tips During Man Overboard Drills on Jones Act Vessels\",\"slug\":\"safety\"},{\"post_title\":\"Safety Tips During Man Overboard Drills on Jones Act Vessels\",\"slug\":\"tips\"},{\"post_title\":\"Safety Tips During Man Overboard Drills on Jones Act Vessels\",\"slug\":\"accident-prevention\"},{\"post_title\":\"Rate Your Injury Claim\",\"slug\":\"value\"},{\"post_title\":\"Punitive Damages & Unseaworthy Vessels\",\"slug\":\"damages\"},{\"post_title\":\"Punitive Damages & Unseaworthy Vessels\",\"slug\":\"seaworthiness\"},{\"post_title\":\"Punitive Damages & Unseaworthy Vessels\",\"slug\":\"punitive\"},{\"post_title\":\"Plane Crash Victims Found Near Lake Pontchartrain\",\"slug\":\"lake-accident\"},{\"post_title\":\"Penalty Policy for Violations of MARPOL Annex VI ECAs\",\"slug\":\"regulations\"},{\"post_title\":\"Offshore Worker Dies on Natural Gas Platform\",\"slug\":\"offshore\"},{\"post_title\":\"Offshore Worker Dies on Natural Gas Platform\",\"slug\":\"death\"},{\"post_title\":\"Offshore Worker Dies on Natural Gas Platform\",\"slug\":\"platform\"},{\"post_title\":\"NOAA ship to map Gulf of Mexico for navigation hazards\",\"slug\":\"hazards\"},{\"post_title\":\"NOAA ship to map Gulf of Mexico for navigation hazards\",\"slug\":\"noaa\"},{\"post_title\":\"NOAA ship to map Gulf of Mexico for navigation hazards\",\"slug\":\"gulf-of-mexico\"},{\"post_title\":\"New Jones Act Tankers Face an Uncertain Future\",\"slug\":\"tankers\"},{\"post_title\":\"More About UWA and the SEMS Program Requirements\",\"slug\":\"training\"},{\"post_title\":\"More About UWA and the SEMS Program Requirements\",\"slug\":\"safety\"},{\"post_title\":\"More About UWA and the SEMS Program Requirements\",\"slug\":\"uwa\"},{\"post_title\":\"Mistakes to Avoid in Your Jones Act Case\",\"slug\":\"tips\"},{\"post_title\":\"Missing JanTran Employee Stephen Miller Found\",\"slug\":\"missing-crew\"},{\"post_title\":\"Merchant Mariner Credential (MMC) Renewal\",\"slug\":\"mmc\"},{\"post_title\":\"Maritime Safety: Overview of American Petroleum Institute Regulations\",\"slug\":\"safety\"},{\"post_title\":\"Maritime Safety: Overview of American Petroleum Institute Regulations\",\"slug\":\"regulations\"},{\"post_title\":\"Maritime Safety: Overview of American Petroleum Institute Regulations\",\"slug\":\"api\"},{\"post_title\":\"Maritime Law Firm Explains Third Party Liability for Maritime Accident\",\"slug\":\"liability\"},{\"post_title\":\"Maritime Law Firm Explains Third Party Liability for Maritime Accident\",\"slug\":\"third-party\"},{\"post_title\":\"Louisiana tugboat company charged with breaking maritime safety laws\",\"slug\":\"safety\"},{\"post_title\":\"Louisiana tugboat company charged with breaking maritime safety laws\",\"slug\":\"violations\"},{\"post_title\":\"Louisiana tugboat company charged with breaking maritime safety laws\",\"slug\":\"tugboat\"},{\"post_title\":\"Longshoreman Killed in Second Philadelphia Port Accident in Two Weeks\",\"slug\":\"longshore\"},{\"post_title\":\"Longshoreman Killed in Second Philadelphia Port Accident in Two Weeks\",\"slug\":\"death\"},{\"post_title\":\"Largest Jones Act Fine on Record Filed Against Oil Company\",\"slug\":\"fines\"},{\"post_title\":\"Largest Jones Act Fine on Record Filed Against Oil Company\",\"slug\":\"oil-company\"},{\"post_title\":\"Jones Act Coverage vs. Workers\u2019 Comp on a Lack of Crew Training Injury\",\"slug\":\"training\"},{\"post_title\":\"Is Your Husband Struggling with an Offshore Injury?\",\"slug\":\"offshore\"},{\"post_title\":\"Is Your Husband Struggling with an Offshore Injury?\",\"slug\":\"spouse\"},{\"post_title\":\"Injured oil worker awarded $15 million for barge accident injuries\",\"slug\":\"barge\"},{\"post_title\":\"Information to Know about Filing a 905(b) Claim under the LHWCA\",\"slug\":\"lhwca\"},{\"post_title\":\"Information to Know about Filing a 905(b) Claim under the LHWCA\",\"slug\":\"longshore\"},{\"post_title\":\"Information to Know about Filing a 905(b) Claim under the LHWCA\",\"slug\":\"905b\"},{\"post_title\":\"How to Report an OSHA Violation\",\"slug\":\"osha\"},{\"post_title\":\"How to Report an OSHA Violation\",\"slug\":\"violations\"},{\"post_title\":\"Gulf of Mexico Seaman Killed in Offshore Crane Accident\",\"slug\":\"crane\"},{\"post_title\":\"Fuel Barge Explosion on Mobile River near Mobile, AL\",\"slug\":\"barge\"},{\"post_title\":\"Fuel Barge Explosion on Mobile River near Mobile, AL\",\"slug\":\"explosion\"},{\"post_title\":\"Five Tips for Proving Negligence in a Jones Act Claim\",\"slug\":\"tips\"},{\"post_title\":\"Five Tips for Proving Negligence in a Jones Act Claim\",\"slug\":\"negligence\"},{\"post_title\":\"Figuring out Fringe Benefits You\u2019ve Lost Out On After an Accident\",\"slug\":\"damages\"},{\"post_title\":\"Figuring out Fringe Benefits You\u2019ve Lost Out On After an Accident\",\"slug\":\"benefits\"},{\"post_title\":\"Do I qualify as a seaman?\",\"slug\":\"seaman\"},{\"post_title\":\"DIY Jones Act Injury Settlement\",\"slug\":\"settlements\"},{\"post_title\":\"Depression Can Hinder Work Injury Recovery\",\"slug\":\"depression\"},{\"post_title\":\"Depression Can Hinder Work Injury Recovery\",\"slug\":\"health\"},{\"post_title\":\"Department of Transportation Supports the Jones Act\",\"slug\":\"dot\"},{\"post_title\":\"Deepwater Horizon Survivors' Fairness Act To Help Families of BP Explosion Victims\",\"slug\":\"bp\"},{\"post_title\":\"Deepwater Horizon Survivors' Fairness Act To Help Families of BP Explosion Victims\",\"slug\":\"explosion\"},{\"post_title\":\"Damages for Unlawful Termination of Injured Seamen in New Orleans\",\"slug\":\"damages\"},{\"post_title\":\"Damages for Unlawful Termination of Injured Seamen in New Orleans\",\"slug\":\"unlawful-termination\"},{\"post_title\":\"Damages for Pain and Suffering\",\"slug\":\"damages\"},{\"post_title\":\"Damages for Pain and Suffering\",\"slug\":\"pain-and-suffering\"},{\"post_title\":\"Damages Available in Emotional Distress Lawsuit Under Jones Act\",\"slug\":\"damages\"},{\"post_title\":\"Damages Available in Emotional Distress Lawsuit Under Jones Act\",\"slug\":\"emotional-distress\"},{\"post_title\":\"Costa Concordia Victims File Multi-Million Dollar Injury Lawsuit\",\"slug\":\"cruise\"},{\"post_title\":\"Compensation in no-fault maritime accidents: Who pays?\",\"slug\":\"compensation\"},{\"post_title\":\"Compensation in no-fault maritime accidents: Who pays?\",\"slug\":\"no-fault\"},{\"post_title\":\"Common Longshoremen and Harbor Worker Injuries\",\"slug\":\"longshore\"},{\"post_title\":\"Common Longshoremen and Harbor Worker Injuries\",\"slug\":\"harbor\"},{\"post_title\":\"Commercial Diver Files Jones Act Case Over Back Injury\",\"slug\":\"back-injuries\"},{\"post_title\":\"Commercial Diver Files Jones Act Case Over Back Injury\",\"slug\":\"diver\"},{\"post_title\":\"Coast Guard requires random drug testing of 50% of crewmen in 2010\",\"slug\":\"regulations\"},{\"post_title\":\"Coast Guard requires random drug testing of 50% of crewmen in 2010\",\"slug\":\"uscg\"},{\"post_title\":\"Coast Guard requires random drug testing of 50% of crewmen in 2010\",\"slug\":\"drug-testing\"},{\"post_title\":\"Coast Guard and OSHA Memorandum of Understanding (MOU)\",\"slug\":\"uscg\"},{\"post_title\":\"Coast Guard and OSHA Memorandum of Understanding (MOU)\",\"slug\":\"osha\"},{\"post_title\":\"Coast Guard and OSHA Memorandum of Understanding (MOU)\",\"slug\":\"mou\"},{\"post_title\":\"Chronic Benzene Exposure and Maritime Hazards Could Lead to a Jones Act Claim\",\"slug\":\"benzene\"},{\"post_title\":\"Caps on Damages for a Jones Act Claim\",\"slug\":\"damages\"},{\"post_title\":\"Caps on Damages for a Jones Act Claim\",\"slug\":\"compensation\"},{\"post_title\":\"Boating Accident Back Injury: Complications of Ruptured Disc\",\"slug\":\"back-injuries\"},{\"post_title\":\"Boating Accident Back Injury: Complications of Ruptured Disc\",\"slug\":\"ruptured-disc\"},{\"post_title\":\"Attorney Fees on Jones Act Cases\",\"slug\":\"attorney-fees\"},{\"post_title\":\"Are you well-rested? Fatigue Considered a Factor in Maritime Casualties\",\"slug\":\"hazards\"},{\"post_title\":\"Are you well-rested? Fatigue Considered a Factor in Maritime Casualties\",\"slug\":\"fatigue\"},{\"post_title\":\"7 Tips for Basic Barge Safety for Maritime Workers\",\"slug\":\"safety\"},{\"post_title\":\"7 Tips for Basic Barge Safety for Maritime Workers\",\"slug\":\"tips\"},{\"post_title\":\"7 Tips for Basic Barge Safety for Maritime Workers\",\"slug\":\"barge\"},{\"post_title\":\"7 Meditation Tips to Protect the Mental Health of Maritime Workers at Sea\",\"slug\":\"tips\"},{\"post_title\":\"7 Meditation Tips to Protect the Mental Health of Maritime Workers at Sea\",\"slug\":\"mental-health\"},{\"post_title\":\"7 Conditions that May Signal an Unseaworthy Vessel in Jones Act Claims\",\"slug\":\"seaworthiness\"},{\"post_title\":\"7 Conditions that May Signal an Unseaworthy Vessel in Jones Act Claims\",\"slug\":\"vessels\"},{\"post_title\":\"5 Types of Compensation Your Jones Act Claim Should Cover\",\"slug\":\"damages\"},{\"post_title\":\"5 Types of Compensation Your Jones Act Claim Should Cover\",\"slug\":\"compensation\"},{\"post_title\":\"5 Types of Compensation Your Jones Act Claim Should Cover\",\"slug\":\"filing-a-claim\"},{\"post_title\":\"3 Coast Guard Maritime Safety Programs Designed to Protect Workers\",\"slug\":\"safety\"},{\"post_title\":\"3 Coast Guard Maritime Safety Programs Designed to Protect Workers\",\"slug\":\"uscg\"}]";
$relationships = json_decode($json_relationships,true);

$inserts = [];
foreach($relationships as $r)
{
    $get_post->execute([$r['post_title']]);
    $get_term->execute([$r['slug']]);
    $inserts[] = [
        'post_id' => $get_post->fetch()['ID'],
        'term_taxonomy_id' => $get_term->fetch()['term_taxonomy_id']
    ];
}

foreach($inserts as $i)
{
    $result = $insert->execute([
        $i['post_id'], $i['term_taxonomy_id']
    ]);
    if($result){
        echo "Relationship inserted for POST ID ... " . $i['post_id'] . "<br />";
    }else{
        echo "Fucking fail ... " . "<br />";
    }
}
