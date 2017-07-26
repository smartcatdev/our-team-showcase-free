<?php

namespace ots;

function add_addons_pages() {

	add_submenu_page( 'edit.php?post_type=team_member', __( 'Add-ons', 'ots' ), __( 'Add-ons', 'ots' ), 'manage_options', 'ots-add-ons', 'ots\do_addons_page' );

}

add_action( 'admin_menu', 'ots\add_addons_pages' );


function do_addons_page() { ?>

	<div class="wrap ots-admin-page ots-add-ons">

		<div class="ad-header">

			<div class="title-bar">

				<div class="inner">

					<div class="branding">
						<img src="<?php echo esc_url( asset( 'images/branding/smartcat-medium.png' ) ); ?>" />
					</div>

					<p class="page-title"><?php _e( 'Our Team Showcase', 'ots' ); ?></p>

				</div>

				<?php if( apply_filters( 'ots_enable_pro_preview', true ) ) : ?>

					<div class="inner">

						<a href="https://smartcatdesign.net/our-team-showcase-demo/"
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

            <div class="addon">
                <img src="<?php echo esc_url( asset( 'images/add-ons/ots-pro.jpg' ) ); ?>" />
                <h2><?php _e( 'Our Team Showcase Pro', 'ots' ); ?></h2>
                <p><?php _e( 'Feature-loaded Pro version of Our Team Showcase adds additional professional templates to your Team Showcase, including a Staff Directory, Honeycomb Layout, Carousel, Stacked Layout, as well as 3 impressive Single Member views: Slide-in Side Panel, Popup V-Card and a Custom Page Single View. It also adds new features such as Team Member Skills, Tags/hobbies, Favorite Content and more!', 'ots' ); ?></p>
                <div>
                    <a target="_blank" href="https://smartcatdesign.net/our-team-showcase-demo/" class="button button-default"><?php _e( 'View Demo', 'ots' ); ?></a>
                    <a target="_blank" href="https://smartcatdesign.net/downloads/our-team-showcase/" class="button button-primary">Learn More</a>
                </div>
            </div>

            <div class="addon">
                <img src="<?php echo esc_url( asset( 'images/add-ons/member-portal.jpg' ) ); ?>" />
                <h2><?php _e( 'Member Login Portal', 'ots' ); ?></h2>
                <p><?php _e( 'Restrict pages & posts to Team Members. Login/Logout/Member Profiles. Creates a private portal on your site for your team members to login, view restricted content, edit their own profile details & profile picture, while giving the site admin complete control, user management, content restriction by group/department and more!', 'ots' ); ?></p>
                <div>
                    <a target="_blank" href="http://wordpressteamplugin.com/member-portal/login/" class="button button-default"><?php _e( 'View Demo', 'ots' ); ?></a>
                    <a target="_blank" href="https://smartcatdesign.net/downloads/member-login-portal/" class="button button-primary"><?php _e( 'Learn More', 'ots' ); ?></a>
                </div>
            </div>

        </div>

	</div>

<?php }
