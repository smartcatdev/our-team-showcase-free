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
function print_attrs( array $attrs, $echo = true ) {

    $html = '';

    foreach( $attrs as $attr => $values ) {
        $html .= ' ' . $attr . '="' . $values . '" ';
    }

    if( $echo ) {
        echo $html;
    }

    return $html;

}


/**
 * Builds a single social link icon.
 *
 * @param  string $link   URL for the link's href.
 * @param  string $icon   URL of the icon to use for the link.s
 * @return string         The HTML for the link.
 * @since 4.0.0
 */
function social_link( $link, $icon = '', $attrs = array(), $before = '', $after = '' ) {

    // See if we're opening links in a new tab
    $target = get_option( Options::SOCIAL_LINK_ACTION ) == 'on' ? '_blank' : false;

    $html = '<a ' . print_attrs( $attrs, false ) . ' ' . ( $target ? 'target="' . $target .'"' : '' ) . ' href="' . $link . '">' . $before;

    if( $icon ) {
        $html .= '<img src="' . esc_url( $icon ) . '"/>';
    }

    $html .= $after . '</a>';

    return $html;

}


/**
 * Loops through a team member's social links and outputs them if they are not empty.
 *
 * @param \WP_Post|null $member
 * @param string        $before HTML to display before the link.
 * @param string        $after  HTML to display after the link.
 * @since 4.0.0
 */
function do_member_social_links( \WP_Post $member = null, $before ='', $after = '' ) {

    $links = array(
        'facebook'  => array( '',        asset( 'images/social/facebook.png'  ) ),
        'twitter'   => array( '',        asset( 'images/social/twitter.png'   ) ),
        'linkedin'  => array( '',        asset( 'images/social/linkedin.png'  ) ),
        'gplus'     => array( '',        asset( 'images/social/gplus.png'     ) ),
        'email'     => array( 'mailto:', asset( 'images/social/email.png'     ) ),
        'phone'     => array( 'tel:',    asset( 'images/social/phone.png'     ) ),
        'pinterest' => array( '',        asset( 'images/social/pinterest.png' ) ),
        'instagram' => array( '',        asset( 'images/social/instagram.png' ) ),
        'website'   => array( '',        asset( 'images/social/website.png'   ) )
    );

    $member = get_post( $member );

    $rendered = '';

    foreach( $links as $meta_key => $data ) {

        $link = get_post_meta( $member->ID, "team_member_$meta_key", true );

        if( !empty( $link ) ) {
            $rendered .= $before . social_link( $data[0] . $link, $data[1], array( 'class' => 'sc_social' ) ) . $after;
        }

    }

    echo apply_filters( 'ots_parse_social_links', $rendered, $member );

}
