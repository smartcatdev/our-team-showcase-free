<?php

namespace ots;


/**
 * Registers the documentation page.
 *
 * @since 4.0.0
 */
function add_documentation_pages() {

    add_submenu_page( 'edit.php?post_type=team_member', __( 'Documentation', 'ots' ), __( 'Documentation', 'ots' ), 'manage_options', 'ots-docs', 'ots\do_documentation_page' );

}

add_action( 'admin_menu', 'ots\add_documentation_pages' );


/**
 * Add sections to the documentation page.
 *
 * @since 4.0.0
 */
function add_documentation_sections() {

    add_settings_section( 'plugin-usage', __( 'Plugin Usage', 'ots' ), '', 'ots-getting-started' );
    add_settings_section( 'templates',  __( 'Templates', 'ots' ), '', 'ots-getting-started' );
    add_settings_section( 'widgets', __( 'Widgets', 'ots' ), '', 'ots-getting-started' );

    add_settings_section( 'manage-members', __( 'Managing Members', 'ots-pro' ), '', 'ots-portal' );
    add_settings_section( 'restrict-posts', __( 'Restricting Posts and Pages', 'ots-pro' ), '', 'ots-portal' );
    add_settings_section( 'portal-usage', __( 'Using the Community Hub', 'ots-pro' ), '', 'ots-portal' );

}

add_action( 'admin_init', 'ots\add_documentation_sections' );


/**
 * Add each document topic to its respective section.
 *
 * @since 4.0.0
 */
function add_documentation_fields() {

    add_settings_field( 'usage', __( 'Usage', 'ots' ), 'ots\doc_usage', 'ots-getting-started', 'plugin-usage' );
    add_settings_field( 'templates', __( 'Team View Templates', 'ots' ), 'ots\doc_templates', 'ots-getting-started', 'templates' );
    add_settings_field( 'shortcode-templates', __( 'Setting a Template and Using Shortcodes', 'ots' ), 'ots\doc_shortcode_templates', 'ots-getting-started', 'templates' );
    add_settings_field( 'shortcode-columns', __( 'Setting The number of columns Using Shortcodes', 'ots' ), 'ots\doc_shortcode_columns', 'ots-getting-started', 'templates' );
    add_settings_field( 'shortcode-ids', __( 'Setting The ID of a Shortcode Instance', 'ots' ), 'ots\doc_shortcode_ids', 'ots-getting-started', 'templates' );
    add_settings_field( 'single-templates', __( 'Single Member View Templates', 'ots' ), 'ots\doc_single_templates', 'ots-getting-started', 'templates' );
    add_settings_field( 'custom-templates', __( 'Custom Templates - <i class="ots-pro">Pro Version</i>', 'ots' ), 'ots\doc_custom_templates', 'ots-getting-started', 'templates' );
	add_settings_field( 'main-widget', __( 'Main Widget', 'ots' ), 'ots\doc_main_widget', 'ots-getting-started', 'widgets' );
    add_settings_field( 'sidebar-widget', __( 'Sidebar Widget', 'ots' ), 'ots\doc_sidebar_widget', 'ots-getting-started', 'widgets' );

    add_settings_field( 'whatis-hub', __( 'What is the Community Hub ?', 'ots-pro' ), 'ots\doc_whatis_hub', 'ots-portal', 'manage-members' );
    add_settings_field( 'manage-members', __( 'Enable or Disable Community Access', 'ots-pro' ), 'ots\doc_manage_members', 'ots-portal', 'manage-members' );
    add_settings_field( 'redirect-posts', __( 'Redirecting Users From Restricted Content', 'ots-pro' ), 'ots\doc_redirect_posts', 'ots-portal', 'restrict-posts' );
    add_settings_field( 'restrict-posts', __( 'Restricting Posts and Pages to Members Only', 'ots-pro' ), 'ots\doc_restrict_posts', 'ots-portal', 'restrict-posts' );
    add_settings_field( 'restrict-groups', __( 'Restricting Posts and Pages by Group', 'ots-pro' ), 'ots\doc_restrict_groups', 'ots-portal', 'restrict-posts' );
    add_settings_field( 'portal-overview', __( 'Community Hub Overview', 'ots-pro' ), 'ots\doc_portal_overview', 'ots-portal', 'portal-usage' );
    add_settings_field( 'editing-profile', __( 'Editing Your Profile', 'ots-pro' ), 'ots\doc_editing_profile', 'ots-portal', 'portal-usage' );
    add_settings_field( 'viewing-profile', __( 'Viewing Others Profiles', 'ots-pro' ), 'ots\doc_viewing_profile', 'ots-portal', 'portal-usage' );
    add_settings_field( 'reset-password', __( 'Resetting Your Password', 'ots-pro' ), 'ots\doc_reset_password', 'ots-portal', 'portal-usage' );

}

add_action( 'admin_init', 'ots\add_documentation_fields' );

/**
 * Render the usage topic.
 *
 * @since 4.0.0
 */
function doc_usage() { ?>

    <div>
        <p>
            <?php _e( 'To display a team showcase on any page of your site, simply place the short-code <code>[our-team]</code> where you want it to appear within the page.', 'ots' ); ?>
        </p>
        <p>
            <?php _e( 'You can also indicate a specific group to display, as well as override the settings for the full team and single member templates through the short-code:', 'ots' ); ?>
            <br>
            <code>[our-team group="slug" template="grid" single_template="panel" columns="3"]</code>
        </p>
        <p>
            <i>
                <strong><?php _e( 'Tip:', 'ots' ); ?></strong>
                <?php _e( 'The group "slug" can be viewed and edited from the plugin settings. Go to Team > Groups and select "Quick Edit" for the group you want to use. The slug should have no capital letters, and use underscores (group_slug) or dashes (group-slug) instead of spaces.', 'ots' ); ?>
            </i>
        </p>
        <p>
            <?php _e( 'Overriding your settings is useful when you want to display the showcase in different ways on different pages.', 'ots' ); ?>
        </p>
    </div>

<?php }


/**
 * Render the templates topic.
 *
 * @since 4.0.0
 */
function doc_templates() { ?>

    <p>
        <?php _e( 'To view the Template options for Our Team Showcase, visit the ', 'ots' ); ?>
        <a target="_blank" href="http://wordpressteamplugin.com/templates/"><?php _e( 'Our Team Showcase Demo Page', 'ots' ); ?></a>.
    </p>
    <p>
        <i>
            <?php _e( 'Please note that <strong>Carousel, Honeycomb, Stacked</strong> and <strong>Directory</strong> are only available in the Pro version.', 'ots' ); ?>
        </i>
    </p>

<?php }


/**
 * Render the short-code topic.
 *
 * @since 4.0.0
 */
function doc_shortcode_templates() { ?>

    <p>
        <?php _e( 'The default template, "Grid - Boxes", can be changed to one of several other options. Each one will display your team showcase in a different visual arrangement. The template can be changed in the ', 'ots' ); ?>
        <a href="<?php menu_page_url( 'ots-settings' ); ?>"><?php _e( 'plugin settings', 'ots' ); ?></a>.
    </p>
    <p>
        <?php _e( 'If you wish to display the Showcase in more than one configuration on the site, you can also modify the short-code to specify a different template for that version of output of the plugin.', 'ots' ); ?>
    </p>
    <p>
        <?php _e( 'Example:', 'ots' ); ?> <code>[our-team template="grid"]</code>
    </p>
    <p>
        <?php _e( 'The short-code values for each of the templates are:', 'ots' ); ?>
    </p>
    <p>
        <code>[our-team template="carousel"]</code>
        <br>
        <code>[our-team template="grid"]</code>
        <br>
        <code>[our-team template="grid_cirlces"]</code>
        <br>
        <code>[our-team template="grid_circles2"]</code>
        <br>
        <code>[our-team template="hc"]</code>
        <br>
        <code>[our-team template="stacked"]</code>
        <br>
        <code>[our-team template="directory"]</code>
    </p>

<?php }


function doc_shortcode_ids() { ?>

    <p>
        <?php _e( 'Each shortcode instance can optionally be given a unique <i>HTML identifier</i>. In the shortcode this makes it easier to modify the behaviour or appearance of individual shortcode instances on the same page. To set the shortcode ID, simply pass the <code>id="your-unique-id"</code> parameter along with any other shortcode options.', 'ots' ); ?>
    </p>

<?php }

function doc_shortcode_columns() { ?>

    <p>
        <?php _e( 'You can set the number of columns from the settings page of the plugin, alternatively, you can also set it from the shortcode directly.', 'ots' ); ?>
    </p>
    
    <p>
        <code>[our-team template="grid3" columns="4"]</code>
    </p>

<?php }


/**
 * Render the single templates topic.
 *
 * @since 4.0.0
 */
function doc_single_templates() { ?>

    <p>
        <strong><?php _e( 'Theme Default (Single Post)', 'ots' ); ?></strong>
    </p>
    <p>
        <?php _e( 'This will load the single member page based on your theme\'s single.php file.', 'ots' ); ?>
    </p>
    <p>
        <strong><?php _e( 'Custom Template', 'ots' ); ?></strong>
    </p>
    <p>
        <?php _e( 'This will load the single member page from a custom template file (team_members_template.php).', 'ots' ); ?>
    </p>
    <p>
        <strong>
            <?php _e( 'Card Popup <code>[our-team single_template="vcard"]</code>', 'ots' ); ?> - <i class="ots-pro"><?php _e( 'Pro Version', 'ots' ); ?></i>
        </strong>
    </p>
    <p class="media">
        <a href="<?php echo esc_url( asset( 'images/demo/card.jpg' ) ); ?>" target="_blank">
            <img src="<?php echo esc_url( asset( 'images/demo/card.jpg' ) ); ?>">
        </a>
    </p>
    <p>
        <?php _e( 'This will load a light-box and the member details in a sliding box.', 'ots' ); ?>
    </p>
    <p>
        <strong>
            <?php _e( 'Side Panel <code>[our-team single_template="panel"]</code>', 'ots' ); ?> - <i class="ots-pro"><?php _e( 'Pro Version', 'ots' ); ?></i>
        </strong>
    </p>
    <p class="media">
        <a href="<?php echo esc_url( asset( 'images/demo/panel-1.jpg' ) ); ?>" target="_blank">
            <img src="<?php echo esc_url( asset( 'images/demo/panel-1.jpg' ) ); ?>">
        </a>
    </p>
    <p>
        <?php _e( 'If you do not wish to include a full single profile for each Team Member, you can disable single member view in the', 'ots' ); ?>
        <a target="_blank" href="<?php menu_page_url( 'ots-settings' ); ?>"><?php _e( ' plugin settings', 'ots' ); ?></a>.
    </p>

<?php }


/**
 * Render the custom templates topic.
 *
 * @since 4.0.0
 */
function doc_custom_templates() { ?>

    <p>
        <?php _e( 'The plugin allows you to choose between several options for displaying single members. By default, the team member single page follows the theme\'s single.php file. You can choose to use the custom template, which is included in the plugin. In the Team plugin Settings page, under Single Member View Settings, select Custom Template. That tells the plugin to use the included custom template file. ', 'ots' ); ?>
    </p>
    <p>
        <strong><?php _e( 'Overriding the Custom Template', 'ots' ); ?></strong>
    </p>
    <p>
        <?php _e( 'To override the file, do not edit it from the plugin. Instead, create the file <code>/{THEME_ROOT}/team_members_template.php</code> and copy the contents of <code>/{PLUGIN_DIR}/templates/team_members_template.php</code> into it. You can then edit this file to your liking.', 'ots' ); ?>
    </p>

<?php }


function doc_main_widget() { ?>

    <p>
        <?php _e( 'The plugin also includes a widget that can output the same templates as the shortcode in your theme\'s widget areas. Simply go to Appearance - Widgets and find the widget titled "Our Team Widget".', 'ots' ); ?>
    </p>
    <p>
        <?php _e( 'You can drag & drop the widget into any widget placeholder and then configure to your liking.', 'ots' ); ?>
    </p>

<?php }


function doc_sidebar_widget() { ?>

    <p>
		<?php _e( 'The plugin comes with an easy to use widget designed for appearing in your site Sidebar. Go to Appearance - Widgets and find the widget titled "Our Team Sidebar Widget".', 'ots' ); ?>
    </p>
    <p class="media">
        <a href="<?php echo esc_url( asset( 'images/demo/sidebar-widget.jpg' ) ); ?>" target="_blank">
            <img src="<?php echo esc_url( asset( 'images/demo/sidebar-widget.jpg' ) ); ?>">
        </a>
    </p>
    <p>
		<?php _e( 'You can drag & drop the widget into any widget placeholder.', 'ots' ); ?>
    </p>

<?php }

function doc_whatis_hub() { ?>

    <p>
        <?php _e( 'Our Community Hub is an add-on for Our Team Showcase, which creates a private, password-protected area on your site that can be only accessed by your team members.', 'ots' ); ?> 
    </p>
    
    <p>
        <?php _e( 'Allow your team members to <strong>login and update</strong> their own user profiles', 'ots' ); ?><br>
        <?php _e( 'Secure posts and pages so that they can only be accessed by your team members', 'ots' ); ?> <br>
        <?php _e( 'A social hub for your company where you can share news, events and updates', 'ots' ); ?> <br>
        
    </p>
    <h2><?php _e( 'Our Community Hub creates a private, password-protected area on your site, where team members can login, view protected posts & pages, write comments, "like" and "favorite" content.' ); ?></h2>
    
    <br>
    

    
    
<?php }

function doc_manage_members() { ?>

    <div>
        <p>
            <?php _e( 'Member Portal access can be enabled or disabled for each team member with the Member Portal meta box. Once enabled, they will be able to log in and start using the portal.', 'ots' ); ?>
        </p>
        <p class="media">
            <a target="_blank" href="<?php echo esc_url( asset( 'images/doc/manage-members-1.jpg' ) ); ?>">
                <img src="<?php echo esc_url( asset( 'images/doc/manage-members-1.jpg' ) ); ?>">
            </a>
        </p>
        <p>
            <?php _e( 'Each members status will display in the <i>Portal Status</i> column the Team Members list table for easy viewing.', 'ots' ); ?>
        </p>
        <p class="media">
            <a target="_blank" href="<?php echo esc_url( asset( 'images/doc/manage-members-2.jpg' ) ); ?>">
                <img src="<?php echo esc_url( asset( 'images/doc/manage-members-2.jpg' ) ); ?>">
            </a>
        </p>
        <p>
            <?php _e( 'The <i>Team Portal</i> widget in the plugin settings page will display helpful information about the status of your member portal. Here you also have the option to enable portal access for all members.', 'ots' ); ?>
        </p>
        <p>
            <i>
                <?php _e( 'Note: When bulk enabling access, all newly activated members will have a new password auto generated and sent to their contact email address', 'ots' ); ?>
            </i>
        </p>
        <p class="media">
            <a target="_blank" href="<?php echo esc_url( asset( 'images/doc/manage-members-3.jpg' ) ); ?>">
                <img src="<?php echo esc_url( asset( 'images/doc/manage-members-3.jpg' ) ); ?>">
            </a>
        </p>
    </div>

<?php }


function doc_redirect_posts() { ?>

    <div>
        <p>
            <?php _e( 'If a user does not have sufficient privileges to view a particular post or page, you can customize the page that they will be redirected to, each can be overridden on a post level.', 'ots' ); ?>
        </p>
        <p class="media">
            <a target="_blank" href="<?php echo esc_url( asset( 'images/doc/restrict-posts-1.jpg' ) ); ?>">
                <img src="<?php echo esc_url( asset( 'images/doc/restrict-posts-1.jpg' ) ); ?>">
            </a>
        </p>
    </div>

<?php }


function doc_restrict_posts() { ?>

    <div>
        <p>
            <?php _e( 'In the <i>Member Portal Restriction</i> meta box you can control who can see a post or page.', 'ots' ); ?>
        </p>
        <p>
            <?php _e( 'Restricting a post to <strong>All logged in members</strong> under Access will cause the post to no longer be public and only viewable only by members who are logged in.', 'ots' ); ?>
        </p>
        <p class="media">
            <a target="_blank" href="<?php echo esc_url( asset( 'images/doc/restrict-posts-2.jpg' ) ); ?>">
                <img src="<?php echo esc_url( asset( 'images/doc/restrict-posts-2.jpg' ) ); ?>">
            </a>
        </p>
    </div>

<?php }


function doc_restrict_groups() { ?>

    <div>
        <p>
            <?php _e( 'Posts and Pages can also be restricted to one or more groups so that only members of those groups will be able to view them.', 'ots' ); ?>
        </p>
        <p class="media">
            <a target="_blank" href="<?php echo esc_url( asset( 'images/doc/restrict-posts-3.jpg' ) ); ?>">
                <img src="<?php echo esc_url( asset( 'images/doc/restrict-posts-3.jpg' ) ); ?>">
            </a>
        </p>
        <p>
            <?php _e( 'The <strong>Portal Status</strong> of each post is also conveniently viewable in the Posts list table.', 'ots' ); ?>
        </p>
        <p class="media">
            <a target="_blank" href="<?php echo esc_url( asset( 'images/doc/restrict-posts-4.jpg' ) ); ?>">
                <img src="<?php echo esc_url( asset( 'images/doc/restrict-posts-4.jpg' ) ); ?>">
            </a>
        </p>
    </div>

<?php }


function doc_reset_password() { ?>

    <p>
        <?php _e( 'If a user has forgotten or lost their password, they can request a new one by selecting the <strong>Forgot Password</strong> link on the login page. A new password will be automatically be generated and sent to their contact email.', 'ots' ); ?>
    </p>

<?php }

function doc_portal_overview() { ?>

    <div>
        <p><?php _e( 'On the portal homepage, members can view previews of recent posts which they can like or comment on. The sidebar contains a list of all pages that the user can access from within the portal and a list of members who are in the same group.', 'ots' ); ?></p>
        <p class="media">
            <img> <code>portal home screenshot</code>
        </p>
        <p>
            <?php _e( 'Users can also click into each post to read the full content and if it is the first time a user is viewing the post, the number of views will be incremented.', 'ots' ); ?>
        </p>
        <p>
            <i><?php _e( 'Tip: Hover over a post\'s views or likes to see which members have viewed or liked the post.', 'ots' ); ?></i>
        </p>
        <p class="media">
            <img> <code>screenshot of likes & views tooltip</code>
        </p>
        <p><?php _e( 'Members can navigate through the portal using the slide-out navigation menu which provides quick links back to the portal home, the user\'s profile page and to log out of the portal.', 'ots' ); ?></p>
        <p class="media">
            <img> <code>screenshot of nav drawer</code>
        </p>
    </div>

<?php }


function doc_editing_profile() { ?>

    <div>
        <p><?php _e( 'On the edit profile page users have the ability to update all aspects of their profile including their name, bio and contact information. Users can also change their password and set their profile image and cover photo.', 'ots' ); ?></p>
        <p>
            <i><?php _e( 'Note: Changes made here will also be reflected on the public site.', 'ots' ); ?></i>
        </p>
        <p class="media">
            <img> <code>screenshot of profile edit page</code>
        </p>
    </div>

<?php }

function doc_viewing_profile() { ?>

    <div>
        <p><?php _e( 'When logged into the portal, users will be able to see a customized version of the single Team Member template that integrates with the team portal\'s appearance.', 'ots' ); ?></p>
        <p class="media">
            <img> <code>screenshot of team member page</code>
        </p>
    </div>

<?php }


/**
 * Render the documentation page.
 *
 * @since 4.0.0
 */
function do_documentation_page() {

    $tabs = array(
        'ots-getting-started' => __( 'Getting Started', 'ots' ),
        'ots-portal'          => __( 'Community Hub', 'ots-pro' )
    );

    $tabs = apply_filters( 'ots_documentation_tabs', $tabs );

    reset( $tabs );

    $active = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $tabs ) ? $_GET['tab'] : key( $tabs );

    ?>

    <div class="wrap ots-admin-page ots-documentation">

        <div class="ots-admin-header">

            <div class="title-bar">

                <div class="inner">

                    <div class="branding">
                        <img src="<?php echo esc_url( asset( 'images/branding/smartcat-medium.jpg' ) ); ?>" />
                    </div>

                    <p class="page-title"><?php _e( 'Our Team Showcase', 'ots' ); ?></p>

                </div>

			    <?php if( apply_filters( 'ots_enable_pro_preview', true ) ) : ?>

                    <div class="inner">

                        <a href="http://wordpressteamplugin.com/templates/"
                           class="cta cta-secondary"
                           target="_blank">
						    <?php _e( 'View Demo', 'ots' ); ?>
                        </a>

                        <a href="https://smartcatdesign.net/downloads/our-team-showcase/"
                           class="cta cta-primary"
                           target="_blank">
						    <?php _e( 'Go Pro', 'ots' ); ?>
                        </a>

                    </div>

			    <?php endif; ?>

            </div>

            <div class="clear"></div>

        </div>

        <h2 style="display: none"></h2>

        <h2 class="nav-tab-wrapper">

            <?php foreach( $tabs as $tab => $title ) : ?>

                <a href="<?php echo esc_url( add_query_arg( 'tab', $tab ) ); ?>"
                   class="nav-tab <?php echo $active == $tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( $title ); ?></a>

            <?php endforeach; ?>

        </h2>

        <div class="inner">

            <form><?php do_settings_sections( $active ); ?></form>

        </div>

    </div>

<?php }