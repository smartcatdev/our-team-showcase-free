<?php

namespace ots;


/**
 * Registers the add-ons submenu page.
 *
 * @since 4.0.0
 */
function add_addons_pages() {

	add_submenu_page( 'edit.php?post_type=team_member', __( 'Add-ons', 'ots' ), __( 'Add-ons', 'ots' ), 'manage_options', 'ots-add-ons', 'ots\do_addons_page' );

}

add_action( 'admin_menu', 'ots\add_addons_pages' );


/**
 * Renders the add-ons admin page.
 *
 * @since 4.0.0
 */
function do_addons_page() { ?>

	<div class="wrap ots-admin-page ots-add-ons">

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

		<div class="inner">

            <div class="ots-add-on">
                <img src="<?php echo esc_url( asset( 'images/add-ons/ots-pro.jpg' ) ); ?>" />
                <h2><?php _e( 'Our Team Showcase Pro', 'ots' ); ?></h2>
                <p><?php _e( 'Feature-loaded Pro version of Our Team Showcase adds additional professional templates to your Team Showcase, including a Staff Directory, Honeycomb Layout, Carousel, Stacked Layout, as well as 4 impressive Single Member views: Slide-in Side Panel, Single with team sidebar,  Popup V-Card and a Custom Page Single View. It also adds new features such as Team Member Skills, Tags/hobbies, Favorite Content and more!', 'ots' ); ?></p>
                <div class="cta">
                    <a target="_blank" href="http://wordpressteamplugin.com/templates/" class="button button-default"><?php _e( 'View Demo', 'ots' ); ?></a>
                    <a target="_blank" href="https://smartcatdesign.net/downloads/our-team-showcase/" class="button button-primary">Go Pro</a>
                </div>
            </div>

            <div class="ots-add-on">
                <img src="<?php echo esc_url( asset( 'images/add-ons/member-portal.jpg' ) ); ?>" />
                <h2><?php _e( 'Member Login Portal', 'ots' ); ?></h2>
                <p><?php _e( 'Restrict pages & posts to Team Members. Login/Logout/Member Profiles. Creates a private portal on your site for your team members to login, view restricted content, edit their own profile details & profile picture, while giving the site admin complete control, user management, content restriction by group/department and more!', 'ots' ); ?></p>
                <div class="cta">
                    <a target="_blank" href="http://wordpressteamplugin.com/member-portal/login/" class="button button-default"><?php _e( 'View Demo', 'ots' ); ?></a>
                    <a target="_blank" href="https://smartcatdesign.net/downloads/member-login-portal/" class="button button-primary"><?php _e( 'Get Portal', 'ots' ); ?></a>
                </div>
            </div>

            <div class="clear"></div>

        </div>

	</div>

<?php }
