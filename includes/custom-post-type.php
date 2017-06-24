<?php

namespace our_team_showcase;

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

//'archives' - String for use with archives in nav menus. Default is Post Archives/Page Archives.
//'attributes' - Label for the attributes meta box. Default is 'Post Attributes' / 'Page Attributes'.
//'insert_into_item' - String for the media frame button. Default is Insert into post/Insert into page.
//'uploaded_to_this_item' - String for the media frame filter. Default is Uploaded to this post/Uploaded to this page.
//'featured_image' - Default is Featured Image.
//'set_featured_image' - Default is Set featured image.
//'remove_featured_image' - Default is Remove featured image.
//'use_featured_image' - Default is Use as featured image.
//'menu_name' - Default is the same as `name`.
//'filter_items_list' - String for the table views hidden heading.
//'items_list_navigation' - String for the table pagination hidden heading.
//'items_list' - String for the table hidden heading.
//'name_admin_bar' - String for use in New in Admin menu bar. Default is the same as `singular_name`.

}

add_action( 'init', 'our_team_showcase\register_custom_post_type' );
