<?php
/**
 * The template for displaying the 'Page' template
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */


$context = Timber::get_context();

# This is the actual Our Team content
$post = new TimberPost();
$context['post'] = $post;
$context['needs_password'] = post_password_required();
get_the_password_form();

$post   = get_post( $post );
$label  = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
$output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
    <p>' . __( 'For client confidentiality, this content is password protected. To view this content, please enter the password below:' ) . '</p>
    <p><label for="' . $label . '">' . __( 'Password:' ) . ' <input name="post_password" id="' . $label . '" type="password" size="20" /></label> <input style="background-color: #1978ba;" type="submit" name="Submit" value="' . esc_attr_x( 'Enter', 'post password form' ) . '" /></p></form>
    ';

/**
 * Filters the HTML output for the protected post password form.
 *
 * If modifying the password field, please note that the core database schema
 * limits the password field to 20 characters regardless of the value of the
 * size attribute in the form input.
 *
 * @since 2.7.0
 *
 * @param string $output The password form HTML output.
 */
$context['password_form'] = apply_filters( 'the_password_form', $output );

# Final rendering
Timber::render( array( 'page.twig' ), $context );