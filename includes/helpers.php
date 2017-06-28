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

    ?>

    <select id="<?php esc_attr_e( $id ); ?>"
            name="<?php esc_attr_e( $name ); ?>"
            class="regular-text">

        <option value=""><?php _e( 'Select an article', 'ots' ); ?></option>

        <?php foreach( $posts as $post ) : ?>

            <option value="<?php esc_attr_e( $post->ID ); ?>"

                <?php selected( $post->ID, $selected ); ?>>

                <?php esc_html_e( $post->post_title ); ?>

            </option>

        <?php endforeach; ?>

    </select>

<?php }


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

function do_member_social_links( \WP_Post $member ) {

    $links = array(
        'facebook'  => '',
        'twitter'   => '',
        'linkedin'  => '',
        'gplus'     => '',
        'email'     => '',
        'phone'     => '',
        'pinterest' => '',
        'instagram' => '',
        'website'   => ''
    );

    foreach( $links as $meta_key => $icon ) {
        echo social_link( get_post_meta( $member->ID, "team_member_$meta_key", true ), $icon );
    }

}