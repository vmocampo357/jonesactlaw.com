<?php

$post_type = 'testimonials';

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

/** @var wpdb $wpdb */
global $wpdb;

$get_attachment_name = $pdo->prepare('SELECT post_title FROM wp_posts WHERE ID = ?');

foreach($prepared_posts as $post)
{
    $get_attachment_name->execute([$post['meta']['_thumbnail_id']]);
    $attachments = $get_attachment_name->fetch();
    if($attachments)
    {
        $title = $attachments['post_title'];
        # Now, we want to see what ID we have in our local table (maritime)

        $our_id = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_title = '".$title."' LIMIT 1");

        $attachment_id = $our_id->ID;

        # Now, let's find our Post
        $existing_posts = get_posts([
            'post_type' => 'testimonials',
            'title' => $post['post']['post_title']
        ]);

        // pretty_print($existing_posts[0]);

        if( set_post_thumbnail( $existing_posts[0], $attachment_id ) )
        {
            echo "Thumbnail added for " . $existing_posts[0]->post_title . "<br />";
        }
    }
}

foreach($prepared_posts as $post)
{
    # First, do we already have this post?
    $post_name = $post['post']['post_title'];

    # Also, set what kind of post type we want this to be
    $post['post']['post_type'] = 'testimonials';

    # Fuck, yea let's also unset the ID
    unset($post['post']['ID']);

    # Ok, so yea, let's check
    $existing_posts = get_posts([
        'post_type' => 'testimonials',
        'title' => $post_name
    ]);

    # Only INSERTS (No updates at this time)
    if(empty($existing_posts)){

        # Insert the POST, finally!
        $post_id = wp_insert_post($post['post'],true);
        if(!is_object($post_id)){
            # Post was created! Let's keep going (this is where it really changes per post type)
            # Start by inserting all the categories
            $terms = [];
            foreach($post['categories'] as $category)
            {
                $terms[] = $loaded_categories[$category['slug']];
            }
            wp_set_object_terms( $post_id, $terms, 'category' );

            echo "Post inserted! -- $post_id<br />";
        }else{
            //TODO: SOMETHING FUCKED UP
            pretty_print($post_id);
        }
    }else{
        echo "There was already a post titled this! <br />";
    }
}
