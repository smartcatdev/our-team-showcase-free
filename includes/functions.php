<?php

namespace ots;

function get_members_in_order( $limit = false, $group = '' ) {

    $limit = $limit ? $limit : get_option( Options::DISPLAY_LIMIT );

    $args = array(
        'post_type'      => 'team_member',
        'posts_per_page' => $limit == 'on' ? -1 : $limit,
        'meta_key'       => 'sc_member_order',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
    );

    if( !empty( $group ) ) {
        $args['team_member_position'] = $group;
    }

    return  new \WP_Query( $args );

}

function get_member_avatar( $member = null, $size = 'post-thumbnail' ) {

    $url = get_the_post_thumbnail_url( $member, $size );

    if( !$url ) {
        $url = asset( 'images/default-avatar.png' );
    }

    return apply_filters( 'ots_member_avatar', $url, $member );

}


function member_avatar( $member = null, $size = 'post_thumbnail' ) {

    if( has_post_thumbnail( $member ) ) {
        the_post_thumbnail( $size );
    } else {

        $img = '<img src="%s" class="attachment-medium wp-post-image" width="%s" height="%s" />';

        printf( $img, asset( 'images/default-avatar.png' ),
            is_array( $size ) ? $size[0] : '',
            is_array( $size ) ? $size[1] : ''
        );

    }

}


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


function sanitize_template( $template ) {

    if( !array_key_exists( $template, get_templates() ) ) {
        $template = get_option( Options::TEMPLATE );
    }

    return $template;

}


function sanitize_single_template( $template ) {

    if( !array_key_exists( $template, get_single_templates() ) ) {
        $template = get_option( Options::SINGLE_TEMPLATE );
    }

    return $template;

}


function get_templates() {

    $templates = array(
        'grid'           => __( 'Grid - Boxes', 'ots' ),
        'grid-circles'   => __( 'Grid - Circles', 'ots' ),
        'grid-circles-2' => __( 'Grid - Circles 2', 'ots' )
    );

    return apply_filters( 'ots_templates', $templates );

}


function get_single_templates() {

    $templates = array(
        'default'       => __( 'Theme Default', 'ots' ),
        'disabled'      => __( 'Disabled', 'ots' )
    );

    return apply_filters( 'ots_single_templates', $templates );

}