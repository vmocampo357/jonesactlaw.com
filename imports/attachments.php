<?php

$post_type = 'attachment';

include('head.php');

include('preparation.php');

/**
 * Now that our Terms are properly set-up, we just INSERT the post now!
 * If it doesn't already exist, of course.
 *
[meta] => Array
(
[_edit_lock] => 1451652624:1
[_edit_last] => 1
[_vc_post_settings] => a:2:{s:7:"vc_grid";a:0:{}s:10:"vc_grid_id";a:0:{}}
[custom_permalink] => faqs/am-i-eligible-to-receive-a-twic-/
)
[categories] => Array
(
[0] => Array
(
[term_id] => 100
[name] => Training
[slug] => training
[term_group] => 0
)

[1] => Array
(
[term_id] => 101
[name] => TWIC
[slug] => twic
[term_group] => 0
)
)
 *
 * =====================================================================================================================
 */

// pretty_print($prepared_posts);

foreach($prepared_posts as $post)
{
    # First, do we already have this post?
    $post_name = $post['post']['post_title'];

    # Fuck, yea let's also unset the ID
    unset($post['post']['ID']);

    // the guid will have the new url though
    $post['post']['guid'] = str_replace("www.jonesactlaw.com","dev.jonesactlaw.com/maritime",$post['post']['guid']);

    // $filename should be the path to a file in the upload directory.
    // http://dev.jonesactlaw.com/maritime/wp-content/uploads/2015/12/tugboat-accidentop.jpg
    $filename = str_replace("http://dev.jonesactlaw.com/","/var/www/dev.jonesactlaw.com/public_html/maritime/",$post['post']['guid']);

    $attachment = $post['post'];

    echo "Processing ... " . $post['post']['guid'] . "<br />";

    // Insert the attachment.
    $attach_id = wp_insert_attachment( $attachment, $filename, 0, true );

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    if(!is_object($attach_id)){
        echo "Attachment added! -- " . $post['post']['post_title'] . "\n";
    }else{
        echo "Could not attach file -- "  . $post['post']['post_title'] . "\n";
        pretty_print($attach_id);
    }
}
