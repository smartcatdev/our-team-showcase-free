<?php

namespace ots;

/**
 * Convenience method to generate a dropdown of posts.
 *
 * @param $name
 * @param string $id
 * @param string $selected
 * @since 4.0.0
 */
function posts_dropdown( $name, $id = '', $selected = '' ) {

    $posts = get_posts( array(
        'post_type'      => 'post',
        'posts_per_page' => -1
    ) );

    echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" class="regular-text">';

    echo '<option value="">' . __( 'Select an article', 'ots' ). '</option>';

    foreach( $posts as $post ) {
        echo '<option value="' . esc_attr( $post->ID ) . '" ' . selected( $post->ID, $selected, false ) . '>' . esc_html( $post->post_title ) . '</option>';
    }

    echo '</select>';

}


/**
 * Prints an array as attributes where key = " attributes ".
 *
 * @param array $attrs
 * @since 4.0.0
 *
 */
function print_attrs( array $attrs ) {

    foreach( $attrs as $attr => $values ) {
        echo ' ' . $attr . '="' . $values . '" ';
    }

}


function social_link( $link, $icon = '' ) {

    // See if we're opening links in a new tab
    $target = get_option( Options::SOCIAL_LINK_ACTION ) == 'on' ? '_blank' : false;

    return '<a ' . ( $target ? 'target="' . $target .'"' : '' ) . ' href="' . esc_url( $link ) . '">
            <img src="' . esc_url( $icon ) . '" class="sc-social" /></a>';

}

function do_member_social_links( \WP_Post $member = null, $before ='', $after = '' ) {

    $links = array(
        'facebook'  => asset( 'images/social/facebook.png' ),
        'twitter'   => asset( 'images/social/twitter.png' ),
        'linkedin'  => asset( 'images/social/linkedin.png' ),
        'gplus'     => asset( 'images/social/gplus.png' ),
        'email'     => asset( 'images/social/email.png' ),
        'phone'     => asset( 'images/social/phone.png' ),
        'pinterest' => asset( 'images/social/pinterest.png' ),
        'instagram' => asset( 'images/social/instagram.png' ),
        'website'   => asset( 'images/social/website.png' )
    );

    $member = get_post( $member );

    foreach( $links as $meta_key => $icon ) {

        $link = get_post_meta( $member->ID, "team_member_$meta_key", true );

        if( !empty( $link ) ) {
            echo $before . social_link( $link, $icon ) . $after;
        }

    }

}