<?php

namespace ots;

/**
 * Register admin menu pages.
 *
 * @since 4.0.0
 */
function add_menu_pages() {

    add_submenu_page( 'edit.php?post_type=team_member', __( 'Our Team Settings', 'ots' ), __( 'Settings', 'ots' ), 'manage_options', 'ots-settings', 'ots\do_settings_page' );

}

add_action( 'admin_menu', 'ots\add_menu_pages' );


/**
 * Register settings with the settings API.
 *
 * @since 4.0.0
 */
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


/**
 * Add settings sections for admin settings pages.
 *
 * @since 4.0.0
 */
function add_settings_sections() {

    add_settings_section( 'team-view-global', __( 'Team View - Global Settings', 'ots' ), '', 'edit.php?post_type=team_member&page=ots-settings' );
    add_settings_section( 'single-member-view-global', __( 'Single Member - Global Settings', 'ots' ), '', 'edit.php?post_type=team_member&page=ots-settings' );

}

add_action( 'admin_init', 'ots\add_settings_sections' );


/**
 * Add settings fields to pages and settings secctions.
 *
 * @since 4.0.0
 */
function add_settings_fields() {

    $display_field_previews = apply_filters( 'ots_show_pro_fields_preview', true );

    /**
     * Team View settings
     *
     * @since 4.0.0
     */
    add_settings_field(
        Options::TEMPLATE,
        __( 'Template', 'ots' ),
        'ots\settings_select_box',
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
        'ots\settings_select_box',
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
        'ots\settings_text_box',
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
        'ots\settings_check_box',
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
        'ots\settings_select_box',
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
        'ots\settings_check_box',
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
        'ots\settings_check_box',
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
        'ots\settings_text_box',
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
        'ots\settings_text_box',
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
        'ots\settings_text_box',
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

    /**
     * Single Member View settings
     *
     * @since 4.0.0
     */
    add_settings_field(
        Options::S_TEMPLATE,
        __( 'Template', 'ots' ),
        'ots\settings_select_box',
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
        'ots\settings_check_box',
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


/**
 * Output the settings page.
 *
 * @since 4.0.0
 */
function do_settings_page() { ?>

    <form method="post" action="options.php">

        <?php do_settings_sections( 'edit.php?post_type=team_member&page=ots-settings' ); ?>

        <?php settings_fields( 'ots-settings' ); ?>

        <?php submit_button(); ?>

    </form>

<?php }

/**
 * Output a select box for a settings field.
 *
 * @param array $args {
 *  string $name     The name of the setting as registered with the settings API.
 *  array  $attrs    An array of HTML attributes for the field.
 *  array  $options  An array of key value pairs that are used for the options.
 *  string $selected The current value of the select box.
 * }
 *
 * @since 4.0.0
 */
function settings_select_box( array $args ) { ?>

    <select name="<?php esc_attr_e( $args['name'] ); ?>"

        <?php if( isset( $args['attrs'] ) ) : print_attrs( $args['attrs'] ); endif; ?> >

        <?php foreach( $args['options'] as $value => $label ) : ?>

            <option value="<?php esc_attr_e( $value ); ?>"
                <?php selected( isset( $args['selected'] ) ? $args['selected'] : '', $value ); ?>>

                <?php esc_html_e( $label ); ?></option>

        <?php endforeach; ?>

    </select>

    <?php if( isset( $args['description'] ) ) : ?>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>

    <?php endif; ?>

<?php }


/**
 * Output a check box for a settings field.
 *
 * @param array $args {
 *  string $name     The name of the setting as registered with the settings API.
 *  array  $attrs    An array of HTML attributes for the field.
 *  array  $checked  Whether or not the checkbox is currently checked.
 *  string $label    The label for the checkbox.
 * }
 *
 * @since 4.0.0
 */
function settings_check_box( array $args ) { ?>

    <label>

        <input name="<?php esc_attr_e( $args['name'] ); ?>"
               type="checkbox"

            <?php if( isset( $args['attrs'] ) ) : print_attrs( $args['attrs'] ); endif; ?>

            <?php checked( 'on', $args['checked'] ); ?>/>

        <?php esc_html_e( $args['label'] ); ?>

    </label>

<?php }

/**
 * Output a text box for a settings field.
 *
 * @param array $args {
 *  string $name        The name of the setting as registered with the settings API.
 *  array  $attrs       An array of HTML attributes for the field.
 *  array  $value       The current value of the text box.
 *  string $description The description to display below the field.
 * }
 *
 * @since 4.0.0
 */
function settings_text_box( array $args ) { ?>

    <input name="<?php esc_attr_e( $args['name'] ); ?>"
           value="<?php echo isset( $args['value'] ) ? esc_attr( $args['value'] ) : ''; ?>"

        <?php if( isset( $args['attrs'] ) ) : print_attrs( $args['attrs'] ); endif; ?> />

    <?php if( isset( $args['description'] ) ) : ?>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>

    <?php endif; ?>

<?php }


/**
 * Outputs disabled placeholder fields.
 *
 * @since 4.0.0
 */
function do_pro_only_field() { ?>

    <p class="description"><?php _e( 'Pro version only', 'ots' ); ?></p>

<?php }