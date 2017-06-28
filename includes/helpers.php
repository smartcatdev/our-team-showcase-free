<?php

namespace ots;

function sanitize_display_limit( $value ) {

    if( $value === 'on' || is_null( $value ) ) {
        return $value;
    } else if( intval( $value ) < 1 ) {
        return get_option( Options::DISPLAY_LIMIT );
    }

    return $value;
}


/**
 * Sanitize a checkbox to either be on or off.
 *
 * @param $value
 * @return bool
 * @since 4.0.0
 */
function sanitize_checkbox( $value ) {

    if( !empty( $value ) && $value !== 'on' ) {
        return false;
    }

    return $value;

}

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


function get_members_in_order() {

    $args = array(
        'post_type'      => 'team_member',
        'posts_per_page' => -1,
        'meta_key'       => 'sc_member_order',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
    );

    return  new \WP_Query( $args );

}

function get_member_avatar( \WP_Post $member ) {

    $url = get_the_post_thumbnail_url( $member );

    if( !$url ) {
        $url = asset( 'images/default-avatar.png' );
    }

    return apply_filters( 'ots_member_avatar', $url, $member );

}