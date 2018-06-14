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

    add_settings_section( 'plugin-usage', __( 'Displaying the team', 'ots' ), '', 'ots-getting-started' );
    add_settings_section( 'templates',  __( 'Templates', 'ots' ), '', 'ots-getting-started' );
    add_settings_section( 'widgets', __( 'Widgets', 'ots' ), '', 'ots-getting-started' );

    add_settings_section( 'manage-members', __( 'Managing Members', 'ots-pro' ), '', 'ots-portal' );
    add_settings_section( 'restrict-posts', __( 'Restricting Posts and Pages', 'ots-pro' ), '', 'ots-portal' );
    add_settings_section( 'portal-usage', __( 'Using the Community Hub', 'ots-pro' ), '', 'ots-portal' );
    
    add_settings_section( 'export-general', __( 'Exporting Team Members', 'ots-pro' ), '', 'ots-import-export' );
    add_settings_section( 'import-general', __( 'Importing Team Members', 'ots-pro' ), '', 'ots-import-export' );

    add_settings_section( 'shortcode-general', __( 'Shortcode Details', 'ots-pro' ), '', 'ots-shortcode' );
    
}

add_action( 'admin_init', 'ots\add_documentation_sections' );


/**
 * Add each document topic to its respective section.
 *
 * @since 4.0.0
 */
function add_documentation_fields() {

    add_settings_field( 'usage', '', 'ots\doc_usage', 'ots-getting-started', 'plugin-usage' );
    add_settings_field( 'templates', __( 'Team View Templates', 'ots' ), 'ots\doc_templates', 'ots-getting-started', 'templates' );
    add_settings_field( 'shortcode-templates', __( 'Setting a Template and Using Shortcodes', 'ots' ), 'ots\doc_shortcode_templates', 'ots-getting-started', 'templates' );
    add_settings_field( 'shortcode-columns', __( 'Setting The number of columns Using Shortcodes', 'ots' ), 'ots\doc_shortcode_columns', 'ots-getting-started', 'templates' );
    add_settings_field( 'shortcode-ids', __( 'Setting The ID of a Shortcode Instance', 'ots' ), 'ots\doc_shortcode_ids', 'ots-getting-started', 'templates' );
    add_settings_field( 'single-templates', __( 'Single Member View Templates', 'ots' ), 'ots\doc_single_templates', 'ots-getting-started', 'templates' );
    add_settings_field( 'override-templates', __( 'Overriding Templates', 'ots' ), 'ots\doc_override_templates', 'ots-getting-started', 'templates' );
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
    
    add_settings_field( 'export-basics', __( 'Export Team Members', 'ots-pro' ), 'ots\doc_export_team', 'ots-import-export', 'export-general' );
    add_settings_field( 'import-basics', __( 'Import Team Members', 'ots-pro' ), 'ots\doc_import_team', 'ots-import-export', 'import-general' );
    
    add_settings_field( 'shortcode-details', __( 'Shortcode Details', 'ots-pro' ), 'ots\doc_shortcode_details', 'ots-shortcode', 'shortcode-general' );

}

add_action( 'admin_init', 'ots\add_documentation_fields' );

/**
 * Render the usage topic.
 *
 * @since 4.0.0
 */
function doc_usage() { ?>

    <div>
        
        <h3>Using the Shortcode</h3>
        This is the primary recommended way to display your team. Simply add <code>[our-team]</code> to any page, post, widget etc.. and that will render the team members.
        You can have multiple shortcodes per page. When you use the shortcode without any parameters, the plugin will display your team members according to the layout and appearance settings you have set in the 
        plugin's <a href="<?php echo esc_url( admin_url( 'edit.php?post_type=team_member&page=ots-settings' ) ) ?>">Settings page.</a>
        <br>
        You can add parameters to the shortcode which allows you to customize the output for each instance of the shortcode.<br><br>
        <strong><a herf="">Click here </a></strong> for a list of all the available Shortcode parameters.
        
        
        <h3>Using the Widgets</h3>
        The plugin creates two custom widgets that you can use to display your team members. <strong>Our Team Widget</strong> and <strong>Our Team Sidebar Widget</strong>.
        You can place these widgets in any of the widget areas provided by your theme.
        
        <h3>Using PHP Code</h3>
        Sometimes you may want to call the shortcode directly from your PHP template code. You can leverage WordPress's <code>do_shortcode</code> method for this.
        <br>
        <code>echo do_shortcode( '[our-team group="developers"]' );</code>
        
        
        
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
            <?php _e( 'Please note that several of these demo templates are only available in the Pro version.', 'ots' ); ?>
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
        <code>[our-team template="grid2"]</code>
        <br>
        <code>[our-team template="grid3"]</code>
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
 * 
 */
function doc_override_templates() { ?>
    
    <p>
        <?php _e( 'Our Team Showcase allows you to customize the team templates, without having to edit the plugin code. ', 'ots' ) ?>
    </p>
    
    <p>
        <?php _e( 'To customize a template, copy the template file that you want to edit from the plugin to the root of your theme, and prefix it with "team-template-". ', 'ots' ) ?>
    </p>
    
    <p>
        <?php _e( 'For example, if you want to customize the default grid template, copy grid.php from <code>our-team-enhanced/templates/grid.php</code> and place it in your theme root. Then edit the file name so it becomes '
                . '<code>team-template-grid.php</code>. Once you do that, you can start making any edits to the template within your theme, and these changes will be maintained when the plugin is updated.', 'ots' ) ?>
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

    <p class="alignright">
        <img src="<?php echo esc_url( asset( 'images/doc/ots-widget.jpg' ) ); ?>"/>
    </p>
    
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
        <?php _e( 'Our Community Hub is included in <strong>Our Team Showcase Pro</strong>, which creates a private, password-protected area on your site that can be only accessed by your team members.', 'ots' ); ?> 
    </p>
    
    <p>
        <?php _e( 'Allow your team members to <strong>login and update</strong> their own user profiles', 'ots' ); ?><br>
        <?php _e( 'Secure posts and pages so that they can only be accessed by your team members', 'ots' ); ?> <br>
        <?php _e( 'A social hub for your company where you can share news, events and updates', 'ots' ); ?> <br>
        
    </p>
    <h2><?php _e( 'Our Community Hub creates a private, password-protected area on your site, where team members can login, view protected posts & pages, write comments, "like" and "favorite" content.' ); ?></h2>
    
    <br>
    
    <p class="media">
        <img src="<?php echo esc_url( asset( 'images/doc/portal-1.jpg' ) ); ?>"/>
    </p>
    
    
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


function doc_export_team() { ?>
    
    <div>
        <p><?php _e( 'The Export feature allows you to export all your team data to a CSV file. This allows you to view the team members in Excel - as well as migrate the team data to a different site or software that you may be using.', 'ots' ); ?></p>
        <p><?php _e( 'To use this feature, go to Import/Export menu item under Team and click the Export button. The plugin will generate an export file and store it in the Uploads folder. You can click the Download button to download a copy of the file.', 'ots' ); ?></p>
    </div>
    
<?php }

function doc_import_team() { ?>
    
    <div>
        <p><?php _e( 'The Import feature allows you to import team members from a csv file. For companies with large staff directories this can be a very effective way of bulk adding new members to the team.', 'ots' ); ?></p>
        <p><?php _e( 'To use this feature, go to Import/Export menu item under Team, select the CSV file that contains your team member data from your computer and click the Import button.', 'ots' ); ?></p>

        <p>
            <a href="//raw.githubusercontent.com/smartcatdev/docs/master/ots/demo-data.csv" class="button button-primary" download="team-export.csv"><?php _e( 'Download Sample CSV', 'ots' ); ?></a>
        </p>

        
        <h3><?php _e( 'Important notes', 'ots' ); ?></h3>
        
        <p>
        <?php _e( '1. Please note that the Import tool will attempt to import member images, this requires the plugin to '
                                            . 'create the image files on the destination website - the web server must have access to create files in PHP '
                                            . 'otherwise the import will fail', 'ots' ); ?>
        </p>
        <p>
        <?php _e( '2. The Import feature will not import posts from the "Favorite articles" - It will simply assign the member\'s favorite posts to the IDs', 'ots' ); ?>    
        </p>
        
        <p>
        <?php _e( '3. The Import feature will not import member groups, or attempt to create them. This is mainly for team member only at this time.', 'ots' ); ?>    
        </p>
        
        <p>
        <?php _e( '4. If you are getting an error that the file you\'re uploading is not a CSV, please ensure that there are no extra spaces or commas in the CSV files.', 'ots' ); ?>    
        </p>
        
    </div>
    
<?php }

/**
 * 
 * @since 4.4
 */
function doc_shortcode_details() { ?>
    
    <table class="widefat data">
        <thead>
            <tr>
                <th>Parameter</th>
                <th>Accepted Values</th>
                <th>Description</th>
            </tr>    
        </thead>
        <tbody>
            <tr>
                <td>group</td>
                <td><i>The slug of the group.</i></td>
                <td>Example: <i>development-team</i></td>
            </tr>
            <tr>
                <td>Template</td>
                <td>
                    grid, grid2, grid3, grid_circles, grid_circles2, hc, stacked, directory
                </td>
                <td>Allows you to set the template for the Team display</td>
            </tr>
            <tr>
                <td>Columns</td>
                <td>
                    1, 2, 3, 4
                </td>
                <td>Set the number of team members per row</td>
            </tr>
            <tr>
                <td>limit</td>
                <td>
                    <i>number</i>
                </td>
                <td>Set the total number of team members to display</td>
            </tr>
            <tr>
                <td>id</td>
                <td>
                    <i>your-unique-id</i>
                </td>
                <td>Give each team member view a unique ID. Useful for developers who want to make customizations per shortcode</td>
            </tr>
            <tr>
                <td>single_template</td>
                <td>
                    vcard, panel, custom, disabled
                </td>
                <td>Give each team member view a unique ID. Useful for developers who want to make customizations per shortcode</td>
            </tr>
            <tr>
                <td>show_filter</td>
                <td>
                    true, false
                </td>
                <td>Display buttons that allow viewers to filter your team members by group. You must have groups with assigned team members for this to work.</td>
            </tr>
            <tr>
                <td>show_search</td>
                <td>
                    true, false
                </td>
                <td>Display a search bar allowing viewers to search team members by name, title, bio etc...</td>
            </tr>
        </tbody>
    
        
    </table>
    
    
<?php }


/**
 * Render the documentation page.
 *
 * @since 4.0.0
 */
function do_documentation_page() {

    $tabs = array(
        'ots-getting-started' => __( 'Getting Started', 'ots' ),
        'ots-portal'          => __( 'Community Hub', 'ots-pro' ),
        'ots-import-export'          => __( 'Import & Export', 'ots-pro' ),
        'ots-shortcode'          => __( 'Shortcode Parameters', 'ots-pro' )
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
                        <img src="<?php echo esc_url( asset( 'images/branding/smartcat-medium.png' ) ); ?>" />
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

