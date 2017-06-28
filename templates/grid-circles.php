<?php

namespace ots;

$members = get_members_in_order();

?>

<?php //TODO fix css files ?>
<div id="sc_our_team" class="grid <?php esc_attr_e( ' sc-col' . get_option( Options::GRID_COLUMNS ) ); ?>">

    <?php if ( $members->have_posts() ) : ?>

        <?php while ( $members->have_posts() ) : $members->the_post(); ?>

            <div itemscope itemtype="http://schema.org/Person" class="sc_team_member">

                <div class="sc_team_member_inner">

                    <img src="<?php echo esc_url( get_member_avatar( get_post() ) ); ?>" />

                    <?php if( get_option( Options::DISPLAY_NAME ) == 'on' ) : ?>

                        <div itemprop="name" class="sc_team_member_name"><?php the_title() ?></div>

                    <?php endif; ?>

                    <?php if( get_option( Options::DISPLAY_TITLE ) == 'on' ) : ?>

                        <div itemprop="jobtitle" class="sc_team_member_jobtitle">
                            <?php echo get_post_meta( get_the_ID(), 'team_member_title', true ); ?>
                        </div>

                    <?php endif; ?>

                    <div class="sc_team_content"><?php the_content(); ?></div>

                    <?php if( get_option( Options::SHOW_SOCIAL ) == 'on' ) : ?>

                        <div class="icons"><?php do_member_social_links( get_post() ); ?></div>

                    <?php endif; ?>

                    <div class="sc_team_member_overlay"></div>

                    <?php if( $get_attr( 'single_template' ) !== 'disabled' ) : ?>

                        <div class="sc_team_more">
                            <a href="<?php the_permalink() ?>" rel="bookmark">
                                <img src="<?php echo esc_url( asset( 'images/more.png' ) ); ?>"/>
                            </a>
                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <?php wp_reset_postdata(); ?>

        <?php endwhile; ?>

    <?php else : ?>

        <p><?php _e( 'No team members have been added yet', 'ots' ); ?></p>

    <?php endif; ?>

    <div class="clear"></div>

</div>
