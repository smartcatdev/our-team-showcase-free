<?php

namespace ots;

?>


<?php get_header(); ?>

<div class="ots-single-wrapper">

        <div class="ots-single-team-member">

            <div class="ots-single-left" itemscope itemtype="http://schema.org/Person">

                <div class="ots-single-inner">

                    <img src="<?php echo get_member_avatar(); ?>" />

                    <h2 class="ots-single-name" itemprop="name"><?php echo the_title(); ?></h2>
                    <h3 class="ots-single-jobtitle" itemprop="jobtitle"><?php echo get_post_meta( get_the_ID(), 'team_member_title', true ); ?></h3>

                    <?php if( get_option( Options::SHOW_SINGLE_SOCIAL ) ) : ?>

                        <ul class="ots-single-social-icons">

                            <?php do_member_social_links( get_post(), '<li>', '</li>' ); ?>

                        </ul>

                    <?php endif; ?>

                </div>

            </div>

            <div class="ots-single-content">

                <?php echo the_content(); ?>

            </div>

        </div>

    </div>

<?php get_footer(); ?>

