<?php

namespace ots;

/**
 * Sanitize a checkbox to either be on or off.
 *
 * @param $value
 * @return bool
 * @since 4.0.0
 */
function sanitize_checkbox( $value ) {

    if( $value != 'on' && $value != 'off' ) {
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
