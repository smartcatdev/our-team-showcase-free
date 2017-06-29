<?php

namespace ots;

?>

<?php get_header(); ?>

    <div class="sc-single-wrapper">

        <?php while ( have_posts() ) : the_post(); ?>

            <div class="sc_team_single_member standard">

                <div class="sc_single_side" itemscope itemtype="http://schema.org/Person">

                    <div class="inner">

                        <?php member_avatar(); ?>

                        <h2 class="name" itemprop="name"><?php echo the_title(); ?></h2>
                        <h3 class="title" itemprop="jobtitle"><?php echo get_post_meta( get_the_ID(), 'team_member_title', true ); ?></h3>

                        <ul class="social <?php echo get_option( Options::SHOW_SINGLE_SOCIAL ) == 'on' ? '' : 'hidden'; ?>">

                            <?php do_member_social_links(); ?>

                        </ul>

                    </div>
                </div>

                <div class="sc_single_main">

                    <?php the_content(); ?>

                </div>

            </div>

        <?php endwhile; ?>

    </div>

<?php get_footer(); ?>

