<?php

namespace ots;

$members = get_members_in_order();

?>

<?php if ( $members->have_posts() ) : ?>

    <?php while ( $members->have_posts() ) : $members->the_post(); ?>

        <div itemscope itemtype="http://schema.org/Person" class="ots-team-member">

            <div class="ots-inner">

                <img class="ots-image" src="<?php echo esc_url( get_member_avatar( get_post() ) ); ?>"/>

                <?php if ( get_option( Options::DISPLAY_NAME ) == 'on' ) : ?>

                    <div itemprop="name" class="ots-name">
                        <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                    </div>

                <?php endif; ?>

                <?php if ( get_option( Options::DISPLAY_TITLE ) == 'on' ) : ?>

                    <div itemprop="jobtitle" class="ots-jobtitle">
                        <span><?php echo get_post_meta( get_the_ID(), 'team_member_title', true ); ?></span>
                    </div>

                <?php endif; ?>

                <?php if ( get_option( Options::SHOW_SOCIAL ) == 'on' ) : ?>

                    <div class="ots-social-icons"><?php do_member_social_links( get_post() ); ?></div>

                <?php endif; ?>

                <div class="ots-overlay"></div>

                <?php if ( $get_attr( 'single_template' ) !== 'disabled' ) : ?>

                    <div class="ots-more">
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
