<?php

/*
Plugin Name: Wood Meta Boxes
Plugin URI: http://pagegwood.com
Description: Custom meta box skeleton plugin
Author: Page G Wood
Version: 1.0
Author URI: http://pagegwood.com
*/


/**
 * Adds a meta box to the post editing screen
 */

function pWood_featured_meta() {
    add_meta_box(
    	'pWood_meta',  //id
    	__( 'Featured Image Display', 'pWood-textdomain' ), //title
    	'pWood_meta_callback', //callback
    	'post', //post
    	'side', //context
    	'low' ); //priority
}
add_action( 'add_meta_boxes', 'pWood_featured_meta' );

/**
 * Outputs the content of the meta box, let's add a checkbox
 */

function pWood_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'pWood_nonce' );
    $pWood_stored_meta = get_post_meta( $post->ID );
    ?>

 <p>
    <span class="pWood-row-title"><?php _e( 'Display featured image on blog post?', 'pWood-textdomain' )?></span>
    <div class="pWood-row-content">
        <label for="featured-checkbox">
            <input type="checkbox" name="featured-checkbox" id="featured-checkbox" value="yes" <?php if ( isset ( $pWood_stored_meta['featured-checkbox'] ) ) checked( $pWood_stored_meta['featured-checkbox'][0], 'yes' ); ?> />
            <?php _e( 'Display image on blog post page', 'pWood-textdomain' )?>
        </label>

    </div>
</p>

    <?php
}

/**
 * Saves the custom meta input
 */
function pWood_meta_save( $post_id ) {

    // Checks save status - overcome autosave, etc.
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'pWood_nonce' ] ) && wp_verify_nonce( $_POST[ 'pWood_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

// Checks for input and saves - save checked as yes and unchecked at no
if( isset( $_POST[ 'featured-checkbox' ] ) ) {
    update_post_meta( $post_id, 'featured-checkbox', 'yes' );
} else {
    update_post_meta( $post_id, 'featured-checkbox', 'no' );
}

}
add_action( 'save_post', 'pWood_meta_save' );
