<?php

namespace ots;

function add_menu_pages() {

    add_submenu_page( 'edit.php?post_type=team_member', __( 'Our Team Settings', 'ots' ), __( 'Settings', 'ots' ), 'manage_options', 'ots-settings', 'ots\do_settings_page' );

}

add_action( 'admin_menu', 'ots\add_menu_pages' );


function register_settings() {

    register_setting( 'ots-settings', Options::TEMPLATE );
    register_setting( 'ots-settings', Options::REWRITE_SLUG );
    register_setting( 'ots-settings', Options::GRID_COLUMNS, 'intval' );
    register_setting( 'ots-settings', Options::MARGIN, 'intval' );
    register_setting( 'ots-settings', Options::SHOW_SOCIAL );
    register_setting( 'ots-settings', Options::SOCIAL_LINK_ACTION );
    register_setting( 'ots-settings', Options::DISPLAY_NAME );
    register_setting( 'ots-settings', Options::DISPLAY_TITLE );
    register_setting( 'ots-settings', Options::DISPLAY_LIMIT );
    register_setting( 'ots-settings', Options::MAIN_COLOR );

}

add_action( 'admin_init', 'ots\register_settings' );


function add_settings_sections() {

    add_settings_section( 'team-view-global', __( 'Team View - Global Settings', 'ots' ), '', 'edit.php?post_type=team_member&page=ots-settings' );
    add_settings_section( 'single-member-view-global', __( 'Single Member - Global Settings', 'ots' ), '', 'edit.php?post_type=team_member&page=ots-settings' );

}

add_action( 'admin_init', 'ots\add_settings_sections' );



function add_settings_fields() {

    $display_field_previews = apply_filters( 'ots_show_pro_fields_preview', true );

    add_settings_field(
        Options::TEMPLATE,
        __( 'Template', 'ots' ),
        'ots\do_select_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'    => Options::TEMPLATE,
            'options' => array(),
            'value'   => get_option( Options::TEMPLATE, Defaults::TEMPLATE ),
            'attrs'   => array( 'class' => 'regular-text' )
        )
    );

    add_settings_field(
        Options::GRID_COLUMNS,
        __( 'Grid Columns', 'ots' ),
        'ots\do_select_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'    => Options::GRID_COLUMNS,
            'options' => array(),
            'value'   => get_option( Options::GRID_COLUMNS, Defaults::GRID_COLUMNS )
        )
    );

    add_settings_field(
        Options::MARGIN,
        __( 'Margin', 'ots' ),
        'ots\do_text_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'    => Options::MARGIN,
            'value'   => get_option( Options::MARGIN, Defaults::MARGIN ),
            'attrs'   => array( 'type' => 'number' )
        )
    );

    add_settings_field(
        Options::SHOW_SOCIAL,
        __( 'Show Social Icons', 'ots' ),
        'ots\do_check_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'        => Options::SHOW_SOCIAL,
            'checked'     => get_option( Options::SHOW_SOCIAL, Defaults::SHOW_SOCIAL ),
            'label'       => __( 'Show social icons', 'ots' )
        )
    );

    add_settings_field(
        Options::SOCIAL_LINK_ACTION,
        __( 'Social Icon Link Action', 'ots' ),
        'ots\do_select_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'    => Options::SOCIAL_LINK_ACTION,
            'options' => array(),
            'value'   => get_option( Options::SOCIAL_LINK_ACTION, Defaults::SOCIAL_LINK_ACTION )
        )
    );

    add_settings_field(
        Options::DISPLAY_NAME,
        __( 'Display Name', 'ots' ),
        'ots\do_check_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'    => Options::DISPLAY_NAME,
            'checked' => get_option( Options::DISPLAY_NAME, Defaults::SOCIAL_LINK_ACTION ),
            'label'   => __( '', 'ots' )
        )
    );

    add_settings_field(
        Options::DISPLAY_TITLE,
        __( 'Display Title', 'ots' ),
        'ots\do_check_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'    => Options::DISPLAY_TITLE,
            'checked' => get_option( Options::DISPLAY_TITLE, Defaults::DISPLAY_TITLE ),
            'label'   => __( '', 'ots' )
        )
    );

    add_settings_field(
        Options::REWRITE_SLUG,
        __( 'Team Member URL Slug', 'ots' ),
        'ots\do_text_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'    => Options::REWRITE_SLUG,
            'value'   => get_option( Options::REWRITE_SLUG, Defaults::REWRITE_SLUG ),
            'attrs'   => array( 'class' => 'regular-text' )
        )
    );

    add_settings_field(
        Options::DISPLAY_LIMIT,
        __( 'Display Limit', 'ots' ),
        'ots\do_text_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'    => Options::DISPLAY_LIMIT,
            'value'   => get_option( Options::DISPLAY_LIMIT, Defaults::DISPLAY_LIMIT ),
            'attrs'   => array( 'type' => 'number' )
        )
    );

    add_settings_field(
        Options::MAIN_COLOR,
        __( 'Main Color', 'ots' ),
        'ots\do_text_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'team-view-global',
        array(
            'name'    => Options::MAIN_COLOR,
            'value'   => get_option( Options::MAIN_COLOR, Defaults::MAIN_COLOR )
        )
    );

    if( $display_field_previews ) {

        add_settings_field( 'pro-max-word-count', __( 'Max Word Count', 'ots' ), 'ots\do_pro_only_field', 'edit.php?post_type=team_member&page=ots-settings', 'team-view-global' );
        add_settings_field( 'pro-name-font-size', __( 'Name Font Size', 'ots' ), 'ots\do_pro_only_field', 'edit.php?post_type=team_member&page=ots-settings', 'team-view-global' );
        add_settings_field( 'pro-title-font-size', __( 'Title Font Size', 'ots' ), 'ots\do_pro_only_field', 'edit.php?post_type=team_member&page=ots-settings', 'team-view-global' );
        add_settings_field( 'pro-icon-style', __( 'Icon Style', 'ots' ),'ots\do_pro_only_field', 'edit.php?post_type=team_member&page=ots-settings', 'team-view-global' );

    }

    // Single member view
    add_settings_field(
        Options::S_TEMPLATE,
        __( 'Template', 'ots' ),
        'ots\do_select_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'single-member-view-global',
        array(
            'name'    => Options::S_TEMPLATE,
            'value'   => get_option( Options::S_TEMPLATE, Defaults::S_TEMPLATE ),
            'options' => array()
        )
    );

    add_settings_field(
        Options::S_SHOW_SOCIAL,
        __( 'Show Social Icons', 'ots' ),
        'ots\do_check_box',
        'edit.php?post_type=team_member&page=ots-settings',
        'single-member-view-global',
        array(
            'name'    => Options::S_SHOW_SOCIAL,
            'checked' => get_option( Options::S_SHOW_SOCIAL, Defaults::S_SHOW_SOCIAL ),
            'label'   => __( '', 'ots' )
        )
    );

    if( $display_field_previews ) {

        add_settings_field( 'pro-card-popup-margin-top', __( 'Card Popup Top Margin', 'ots' ), 'ots\do_pro_only_field', 'edit.php?post_type=team_member&page=ots-settings', 'single-member-view-global' );
        add_settings_field( 'pro-display-skills-bar', __( 'Display Skills Bar', 'ots' ), 'ots\do_pro_only_field', 'edit.php?post_type=team_member&page=ots-settings', 'single-member-view-global' );
        add_settings_field( 'pro-skills-title', __( 'Skills Title', 'ots' ), 'ots\do_pro_only_field', 'edit.php?post_type=team_member&page=ots-settings', 'single-member-view-global' );
        add_settings_field( 'pro-image-style', __( 'Image Style', 'ots' ),'ots\do_pro_only_field', 'edit.php?post_type=team_member&page=ots-settings', 'single-member-view-global' );

    }

}

add_action( 'admin_init', 'ots\add_settings_fields' );


function do_settings_page() { ?>

    <form method="post" action="options.php">

        <?php do_settings_sections( 'edit.php?post_type=team_member&page=ots-settings' ); ?>

        <?php settings_fields( 'ots-settings' ); ?>

        <?php submit_button(); ?>

    </form>

<?php }