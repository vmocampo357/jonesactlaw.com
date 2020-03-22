<?php
/**
 * Loop through each Post now to get 3 different things:
 * - Metadata
 * - Categories
 * - Author Information
 * =====================================================================================================================
 */
$prepared_posts = [];
$prepared_categories = [];
$prepared_meta = [];

foreach($posts as $post)
{

    # Create a new, prepared Post
    $prepared_post['post'] = $post;

    # Get all the meta-data -- we'll loop through this to create a Post
    $get_metadata->execute([$post['ID']]);
    $metadata = $get_metadata->fetchAll();
    foreach($metadata as $meta)
    {
        $prepared_meta[$meta['meta_key']] = 1;
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



/**
 * Go through each prepared Category and let's load the WP_Category into it,
 * or alternatively, if it doesn't exist, insert it
 *  [maintenance-and-cure] => Array
 *      (
 *          [term_id] => 102
 *          [name] => Maintenance & Cure
 *          [slug] => maintenance-and-cure
 *          [term_group] => 0
 *          [taxonomy] => category
 *      )
 */
$loaded_categories = [];
foreach($prepared_categories as $slug => $category)
{
    # See if we have the term, by chance
    $term = get_term_by('slug', $slug, $category['taxonomy']);

    # If we don't find the term, we insert, and use that as our Term
    if(!$term){

        # Try to insert this term now
        $result = wp_insert_term($category['name'], $category['taxonomy'], [
            'slug' => $slug
        ]);

        # If we have an array as a result, continue
        if(is_array($result)){
            $new_term_id = $result['term_id'];
            $new_term = get_term($new_term_id);
            $loaded_categories[$slug] = $new_term->term_id;
        }else{
            //TODO: SOMETHING FUCKED UP
        }
    }else{
        $loaded_categories[$slug] = $term->term_id;
    }
}