<?php

namespace ots;


function enqueue_settings_scripts( $hook ) {

    if( strpos( $hook, 'ots-settings' ) ) {
        wp_enqueue_script( 'ots-settings-js', asset( 'admin/js/settings.js' ), array( 'jquery', 'wp-color-picker' ), VERSION );
        wp_enqueue_style( 'ots-settings-css', asset( 'admin/css/settings.css' ), null, VERSION );
        wp_enqueue_style( 'wp-color-picker' );
    }

}

add_action( 'admin_enqueue_scripts', 'ots\enqueue_settings_scripts' );


/**
 * Register admin menu pages.
 *
 * @since 4.0.0
 */
function add_menu_pages() {

    add_submenu_page( 'edit.php?post_type=team_member', __( 'Re-Order Members', 'ots' ), __( 'Re-Order Members', 'ots' ), 'manage_options', 'ots-reorder-members', 'ots\do_member_reorder_page' );
    add_submenu_page( 'edit.php?post_type=team_member', __( 'Our Team Settings', 'ots' ), __( 'Settings', 'ots' ), 'manage_options', 'ots-settings', 'ots\do_settings_page' );

}

add_action( 'admin_menu', 'ots\add_menu_pages' );


/**
 * Register settings with the settings API.
 *
 * @since 4.0.0
 */
function register_settings() {

    register_setting( 'ots-team-view', Options::TEMPLATE, array(
        'type'              => 'string',
        'default'           => Defaults::TEMPLATE,
        'sanitize_callback' => 'ots\sanitize_template'
    ) );

    register_setting( 'ots-team-view', Options::REWRITE_SLUG, array(
        'type'              => 'string',
        'default'           => Defaults::REWRITE_SLUG,
        'sanitize_callback' => 'sanitize_title'
    ) );

    register_setting( 'ots-team-view', Options::GRID_COLUMNS, array(
        'type'              => 'integer',
        'default'           => Defaults::GRID_COLUMNS,
        'sanitize_callback' => 'intval'
    ) );

    register_setting( 'ots-team-view', Options::MARGIN, array(
        'type'              => 'integer',
        'default'           => Defaults::MARGIN,
        'sanitize_callback' => 'intval'
    ) );

    register_setting( 'ots-team-view', Options::SHOW_SOCIAL, array(
        'type'              => 'string',
        'default'           => Defaults::SHOW_SOCIAL,
        'sanitize_callback' => 'ots\sanitize_checkbox'
    ) );

    register_setting( 'ots-team-view', Options::SOCIAL_LINK_ACTION, array(
        'type'              => 'string',
        'default'           => Defaults::SOCIAL_LINK_ACTION,
        'sanitize_callback' => 'ots\sanitize_checkbox'
    ) );

    register_setting( 'ots-team-view', Options::DISPLAY_NAME, array(
        'type'              => 'string',
        'default'           => Defaults::DISPLAY_NAME,
        'sanitize_callback' => 'ots\sanitize_checkbox'
    ) );

    register_setting( 'ots-team-view', Options::DISPLAY_TITLE, array(
        'type'              => 'string',
        'default'           => Defaults::DISPLAY_TITLE,
        'sanitize_callback' => 'ots\sanitize_checkbox'
    ) );

    register_setting( 'ots-team-view', Options::DISPLAY_LIMIT, array(
        'default'           => Defaults::DISPLAY_LIMIT,
        'sanitize_callback' => 'ots\sanitize_display_limit'
    ) );

    register_setting( 'ots-team-view', Options::MAIN_COLOR, array(
        'type'              => 'string',
        'default'           => Defaults::DISPLAY_TITLE,
        'sanitize_callback' => 'sanitize_hex_color'
    ) );

    register_setting( 'ots-single-member-view', Options::SINGLE_TEMPLATE, array(
        'type'              => 'string',
        'default'           => Defaults::S_TEMPLATE,
        'sanitize_callback' => 'ots\sanitize_single_template'
    ) );

}

add_action( 'admin_init', 'ots\register_settings' );


/**
 * Add settings sections for admin settings pages.
 *
 * @since 4.0.0
 */
function add_settings_sections() {

    add_settings_section( 'ots-team-view', __( 'Team View', 'ots' ), '', 'ots-team-view' );
    add_settings_section( 'ots-single-member-view', __( 'Single Member', 'ots' ), '', 'ots-single-member-view' );

}

add_action( 'admin_init', 'ots\add_settings_sections' );


/**
 * Add settings fields to pages and settings secctions.
 *
 * @since 4.0.0
 */
function add_settings_fields() {

    $display_field_previews = apply_filters( 'ots_show_pro_fields_preview', true );

    $templates = array( '' => __( 'Select a template', 'ots' ) ) + get_templates();

    $preview_templates = !$display_field_previews ? array() : array(
        'list-stacked'    => __( 'List - Stacked (Pro)', 'ots' ),
        'honey-comb'      => __( 'Honey Comb (Pro)', 'ots' ),
        'carousel'        => __( 'Carousel (Pro)', 'ots' ),
        'staff-directory' => __( 'Staff Directory (Pro)' )
    );


    /**
     * Team View settings
     *
     * @since 4.0.0
     */
    add_settings_field(
        Options::TEMPLATE,
        __( 'Template', 'ots' ),
        'ots\settings_select_box',
        'ots-team-view',
        'ots-team-view',
        array(
            'name'             => Options::TEMPLATE,
            'selected'         => get_option( Options::TEMPLATE ),
            'attrs'            => array( 'class' => 'regular-text' ),
            'options'          => $templates + $preview_templates,
            'disabled_options' => array_keys( $preview_templates )
        )
    );

    add_settings_field(
        Options::GRID_COLUMNS,
        __( 'Grid Columns', 'ots' ),
        'ots\settings_select_box',
        'ots-team-view',
        'ots-team-view',
        array(
            'name'    => Options::GRID_COLUMNS,
            'selected' => get_option( Options::GRID_COLUMNS ),
            'options' => array(
                2  => 2,
                3  => 3,
                4  => 4,
                5  => 5,
                10 => 10
            )
        )
    );

    add_settings_field(
        Options::MARGIN,
        __( 'Margin', 'ots' ),
        'ots\settings_select_box',
        'ots-team-view',
        'ots-team-view',
        array(
            'name'     => Options::MARGIN,
            'selected' => get_option( Options::MARGIN ),
            'options'  => array(
                0  => __( 'No Margin', 'ots' ),
                5  => 5,
                10 => 10,
                15 => 15
            )
        )
    );

    add_settings_field(
        Options::SHOW_SOCIAL,
        __( 'Show Social Icons', 'ots' ),
        'ots\settings_check_box',
        'ots-team-view',
        'ots-team-view',
        array(
            'name'        => Options::SHOW_SOCIAL,
            'checked'     => get_option( Options::SHOW_SOCIAL ),
            'label'       => __( 'Show social icons', 'ots' )
        )
    );

    add_settings_field(
        Options::SOCIAL_LINK_ACTION,
        __( 'Social Links', 'ots' ),
        'ots\settings_check_box',
        'ots-team-view',
        'ots-team-view',
        array(
            'name'    => Options::SOCIAL_LINK_ACTION,
            'checked' => get_option( Options::SOCIAL_LINK_ACTION ),
            'label'   => __( 'Open social links in a new tab', 'ots' )
        )
    );

    add_settings_field(
        Options::DISPLAY_NAME,
        __( 'Display Name', 'ots' ),
        'ots\settings_check_box',
        'ots-team-view',
        'ots-team-view',
        array(
            'name'    => Options::DISPLAY_NAME,
            'checked' => get_option( Options::DISPLAY_NAME ),
            'label'   => __( '', 'ots' )
        )
    );

    add_settings_field(
        Options::DISPLAY_TITLE,
        __( 'Display Title', 'ots' ),
        'ots\settings_check_box',
        'ots-team-view',
        'ots-team-view',
        array(
            'name'    => Options::DISPLAY_TITLE,
            'checked' => get_option( Options::DISPLAY_TITLE ),
            'label'   => __( '', 'ots' )
        )
    );

    add_settings_field(
        Options::REWRITE_SLUG,
        __( 'Team Member URL Slug', 'ots' ),
        'ots\settings_text_box',
        'ots-team-view',
        'ots-team-view',
        array(
            'name'    => Options::REWRITE_SLUG,
            'value'   => get_option( Options::REWRITE_SLUG  ),
            'attrs'   => array( 'class' => 'regular-text' )
        )
    );

    add_settings_field(
        Options::DISPLAY_LIMIT,
        __( 'Display Limit', 'ots' ),
        'ots\display_limit_field',
        'ots-team-view',
        'ots-team-view'
    );

    add_settings_field(
        Options::MAIN_COLOR,
        __( 'Main Color', 'ots' ),
        'ots\settings_text_box',
        'ots-team-view',
        'ots-team-view',
        array(
            'name'    => Options::MAIN_COLOR,
            'value'   => get_option( Options::MAIN_COLOR )
        )
    );

    if( $display_field_previews ) {

        add_settings_field( 'pro-max-word-count', __( 'Max Word Count', 'ots' ), 'ots\do_pro_only_field', 'ots-team-view', 'ots-team-view' );
        add_settings_field( 'pro-name-font-size', __( 'Name Font Size', 'ots' ), 'ots\do_pro_only_field', 'ots-team-view', 'ots-team-view' );
        add_settings_field( 'pro-title-font-size', __( 'Title Font Size', 'ots' ), 'ots\do_pro_only_field', 'ots-team-view', 'ots-team-view' );
        add_settings_field( 'pro-icon-style', __( 'Icon Style', 'ots' ),'ots\do_pro_only_field', 'ots-team-view', 'ots-team-view' );

    }

    $templates = array( '' => __( 'Select a template', 'ots' ) ) + get_single_templates();

    $preview_templates = !$display_field_previews ? array() : array(
        'custom'     => __( 'Custom Template (Pro)', 'ots' ),
        'card-popup' => __( 'Card - Popup (Pro)', 'ots' ),
        'side-panel' => __( 'Side Panel (Pro)', 'ots' )
    );


    /**
     * Single Member View settings
     *
     * @since 4.0.0
     */
    add_settings_field(
        Options::SINGLE_TEMPLATE,
        __( 'Template', 'ots' ),
        'ots\settings_select_box',
        'ots-single-member-view',
        'ots-single-member-view',
        array(
            'name'             => Options::SINGLE_TEMPLATE,
            'selected'         => get_option( Options::SINGLE_TEMPLATE ),
            'attrs'            => array( 'class' => 'regular-text' ),
            'options'          => $templates + $preview_templates,
            'disabled_options' => array_keys( $preview_templates )
        )
    );


    if( $display_field_previews ) {

        add_settings_field( 'pro-card-popup-margin-top', __( 'Card Popup Top Margin', 'ots' ), 'ots\do_pro_only_field', 'ots-single-member-view', 'ots-single-member-view' );
        add_settings_field( 'pro-display-skills-bar', __( 'Display Skills Bar', 'ots' ), 'ots\do_pro_only_field', 'ots-single-member-view', 'ots-single-member-view' );
        add_settings_field( 'pro-skills-title', __( 'Skills Title', 'ots' ), 'ots\do_pro_only_field', 'ots-single-member-view', 'ots-single-member-view' );
        add_settings_field( 'pro-image-style', __( 'Image Style', 'ots' ),'ots\do_pro_only_field', 'ots-single-member-view', 'ots-single-member-view' );

    }

}

add_action( 'admin_init', 'ots\add_settings_fields' );


function team_member_slug_changed( $option ) {

    if( $option === Options::REWRITE_SLUG ) {
        register_team_member_post_type();
        flush_rewrite_rules();
    }

}

add_action( 'updated_option', 'ots\team_member_slug_changed' );


/**
 * Output the settings page.
 *
 * @since 4.0.0
 */
function do_settings_page() {

    $tabs = apply_filters( 'ots_settings_page_tabs',  array(
        'ots-team-view'          => __( 'Team View', 'ots' ),
        'ots-single-member-view' => __( 'Single Member View', 'ots' )
    ) );

    reset( $tabs );

    $active = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $tabs ) ? $_GET['tab'] : key( $tabs );
    $screen = get_current_screen();

    ?>

    <div class="wrap ots-admin-page">

        <div class="inner">

            <div class="ad-header">

                <h2><?php _e( 'Our Team Showcase', 'ots' ); ?></h2>

                <button class="cta-primary"><?php _e( 'Go Pro', 'ots' ); ?></button>

            </div>

            <?php settings_errors(); ?>

            <h2 class="nav-tab-wrapper">

                <?php foreach( $tabs as $tab => $title ) : ?>

                    <a href="<?php echo $screen->parent_file . '&page=ots-settings&tab=' . $tab; ?>"
                       class="nav-tab <?php echo $active == $tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( $title ); ?></a>

                <?php endforeach; ?>

            </h2>

            <form method="post" action="options.php">

                <?php do_settings_sections( $active ); ?>

                <?php settings_fields( $active ); ?>

                <?php submit_button(); ?>

            </form>

        </div>

    </div>

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
                <?php selected( $value, isset( $args['selected'] ) ? $args['selected'] : '' ); ?>

                <?php if( !empty( $args['disabled_options'] ) ) : ?>

                    <?php disabled( true, in_array( $value, $args['disabled_options'] ) ); ?>

                <?php endif; ?>

                ><?php esc_html_e( $label ); ?></option>

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


function display_limit_field() { ?>

    <?php $value = get_option( Options::DISPLAY_LIMIT ); ?>

    <input type="number"
           min="1"
           id="ots-display-limit-number"
           name="<?php esc_attr_e( Options::DISPLAY_LIMIT ); ?>"
           value="<?php $value !== 'on' ? esc_attr_e( $value ) : ''; ?>"

           <?php disabled( $value, 'on' ); ?> >

    <?php _e( ' - or - ', 'ots' ); ?>

    <label style="display: inline-block; margin: 10px 0;">

        <input type="checkbox"
               id="ots-display-limit-all"
               name="<?php esc_attr_e( Options::DISPLAY_LIMIT ); ?>"

                <?php checked( $value, 'on' ); ?> >

        <?php _e( 'Display all', 'ots' ); ?>

    </label>

<?php }
