<?php

namespace ots;

/**
 * Register the team member custom post type.
 *
 * @since 4.0.0
 */
function register_custom_post_type() {

    $labels = array(
        'name'               => _x( 'Team Members', 'post type general name', 'ots' ),
        'singular_name'      => _x( 'Team Member', 'post type singular name', 'ots' ),
        'menu_name'          => _x( 'All Team Members', 'admin menu', 'ots' ),
        'name_admin_bar'     => _x( 'Team Member', 'add new on admin bar', 'ots' ),
        'add_new'            => _x( 'Add New', 'team_member', 'ots' ),
        'add_new_item'       => __( 'Add New Member', 'ots' ),
        'new_item'           => __( 'New Member', 'ots' ),
        'edit_item'          => __( 'Edit Member', 'ots' ),
        'view_item'          => __( 'View Member', 'ots' ),
        'all_items'          => __( 'All Members', 'ots' ),
        'search_items'       => __( 'Search Members', 'ots' ),
        'parent_item_colon'  => __( 'Parent Members:', 'ots' ),
        'not_found'          => __( 'No members found.', 'ots' ),
        'not_found_in_trash' => __( 'No members found in Trash.', 'ots' ),
        'archives'           => __( 'Member Archives', 'ots' ),
        'attributes'         => __( 'Member Attributes', 'ots' ),

    );

    $args = array(
        'menu_icon'           => 'dashicons-admin-users',
        'labels'              => $labels,
        'capability_type'     => 'post',
        'register_meta_box_cb' => 'ots\do_team_member_meta_box',
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'public'              => true,
        'rewrite'             => array(
            'slug'  => get_option( Options::REWRITE_SLUG, Defaults::REWRITE_SLUG )
        )
    );

    register_post_type( 'team_member', $args );

}

add_action( 'init', 'ots\register_custom_post_type' );

/**
 * Renders the member custom post type metabox fields
 *
 * @since 4.0.0
 */
function do_team_member_meta_box() { ?>



<?php }