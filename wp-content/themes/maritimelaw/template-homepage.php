<?php
/**
 * Template Name: Maritime Law Homepage
 * Description: Homepage specific template, should be used as a Front Page under read settings
 */
/**
 * The template for displaying the homepage
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */


$context = Timber::get_context();

/**
 * This is the normal page we'll render, if we weren't searching for anything
 */

# This is the actual homepage content
$post = new TimberPost();
$context['post'] = $post;

# These are the practice areas we'll loop through
# other posts
$context['practice_areas'] = \Timber\Timber::get_posts([
    'post_type' => 'practice-areas',
    'posts_per_page' => -1,
    'post_status' => 'publish'
]);

# Grab a testimonial video that is both 'sticky' and has a video.
# If we find one, we'll use it for the page--otherwise, nothing will show there
$testimonials = [];
$context['testimonials_available'] = $testimonials = \Timber\Timber::get_posts([
    'post_type' => 'testimonials',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key'     => 'tst_testimonial_video',
            'value'   => '',
            'compare' => '!=',
        ),
        array(
            'key'     => 'tst_featured_testimonial',
            'value'   => true,
            'compare' => '=',
        ),
    ),
]);
$testimonial_found = false;
if(count($testimonials) > 0)
{
    $testimonial_found = true;
}

$context['is_mobile'] = (!wp_is_mobile());
$context['testimonial_found'] = $testimonial_found;
$context['testimonial'] = ( $testimonial_found ) ? $testimonials[0] : [];
$context['is_home_page'] = true;

Timber::render( array( 'page-template-homepage.twig' ), $context );

