<?php

namespace ots;


/**
 * Receives the orders array of team member IDs and loops through them to update their position meta.
 *
 * @since 4.0.0
 */
function save_team_members_order() {

        if( isset( $_POST['member_order_nonce'] ) &&
            wp_verify_nonce( $_POST['member_order_nonce'], 'save_members_order' ) ) {

            $ids = array();

            // Extract the URL encoded IDs
            parse_str( $_POST['members_order'], $ids );


            for( $ctr = 1; $ctr < count( $ids['member'] ) + 1; $ctr++ ) {
                update_post_meta( $ids['member'][ $ctr - 1 ], 'sc_member_order', $ctr );
            }


            add_settings_error( 'ots-reorder', 'save-order', __( 'Order Successfully updated', 'ots' ), 'updated' );

        }

}

add_action( 'admin_init', 'ots\save_team_members_order' );


/**
 * Output the admin page for reordering team members.
 *
 * @since 4.0.0
 */
function do_member_reorder_page() { ?>

    <?php $members = get_members_in_order( false ); ?>

    <div class="wrap">

        <?php settings_errors( 'ots-reorder' ); ?>

        <h2><?php _e( 'Drag & Drop to Re-Order Team Members', 'ots' ); ?></h2>

        <?php if( $members->have_posts() ) : ?>

            <form method="post">

                <ul id="ots-team-member-order">

                    <?php foreach( $members->posts as $member ) : ?>

                        <li id="member_<?php esc_attr_e( $member->ID ); ?>" class="ots-member">

                            <a class="edit" href="<?php echo esc_url( get_edit_post_link( $member->ID ) ); ?>">
                                <span class="dashicons dashicons-edit"></span>
                            </a>

                            <div class="inner">

                                <div class="thumbnail" style="background-image: url('<?php echo esc_url( get_member_avatar( $member ) ); ?>');"></div>

                                <div class="member-info">
                                    <h2 class="name"><?php echo esc_html_e( $member->post_title ); ?></h2>
                                    <p class="description job-title"><?php esc_html_e( get_post_meta( $member->ID, 'team_member_title', true ) ); ?></p>
                                </div>

                            </div>

                        </li>

                    <?php endforeach; ?>

                </ul>

                <input type="hidden" name="members_order" />
                <input type="hidden" name="action" value="ots_save_members_order" />

                <?php wp_nonce_field( 'save_members_order', 'member_order_nonce' ); ?>

                <?php submit_button( __( 'Save Order', 'ots' ) ); ?>

            </form>

        <?php else : ?>

            <h3><?php _e( 'You haven\'t added any team members yet. <a href="edit.php?post_type=team_member">Add a new member</a>', 'ots' ); ?></h3>

        <?php endif; ?>

    </div>

<?php }