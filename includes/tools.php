<?php

namespace ots;

add_action( 'admin_post_ots_export_team', function() {
    
    $message = array();
    
    $team = new \WP_Query( array(
        'post_type'     => 'team_member'
    ) );
    
    
    if( $team->post_count <= 0 ) {
        
        $message['type'] = 'fail';
        $message['output'] = __( 'There are no team members to export', 'ots' );
        
    }else {
        
        
        $members = array();
        
        foreach( $team->posts as $member ) {
            
            $members[ $member->ID ] = array(
                'name'              => $member->post_title,
                'title'             => get_post_meta( $member->ID, 'team_member_title', true ),
                'email'             => get_post_meta( $member->ID, 'team_member_email', true ),
                'phone'             => get_post_meta( $member->ID, 'team_member_phone', true ),
                'facebook_url'      => get_post_meta( $member->ID, 'team_member_links[facebook]', true ),
                'twitter_url'       => get_post_meta( $member->ID, 'team_member_links[twitter]', true ),
                'linkedin_url'      => get_post_meta( $member->ID, 'team_member_links[linkedin]', true ),
                'instagram_url'     => get_post_meta( $member->ID, 'team_member_links[instagram]', true ),
                'pinterest_url'     => get_post_meta( $member->ID, 'team_member_links[pinterest]', true ),
                'website_url'       => get_post_meta( $member->ID, 'team_member_links[website]', true ),
                'other_icon'        => get_post_meta( $member->ID, 'team_member_links[other_icon]', true ),
                'other_url'         => get_post_meta( $member->ID, 'team_member_links[other_url]', true ),
                'display_articles'  => get_post_meta( $member->ID, 'team_member_article_bool', true ),
                'article_title'     => get_post_meta( $member->ID, 'team_member_article_title', true ),
                'article_1'         => get_post_meta( $member->ID, 'team_member_articles[1]', true ),
                'article_2'         => get_post_meta( $member->ID, 'team_member_articles[2]', true ),
                'article_3'         => get_post_meta( $member->ID, 'team_member_articles[3]', true ),
            );
              
        }
        
        $csv = put_csv( $members );
        
        $dir = wp_upload_dir();
        $file_path = $dir['path'] . '/export.csv';
        
        $message['type'] = 'fail';
        $message['output'] = __( 'There are no team members to export', 'ots' );
        
        $file = fopen( $file_path, 'w' );
        fputs( $file, $csv );
        fclose( $file );
        
    }
    
});

/**
 * 
 * Takes associative array and returns a comma separated string
 * 
 * @param type Array
 * @return String
 */
function put_csv( $data ) {
    
    $fh = fopen( 'php://temp', 'rw' ); 
    
    fputcsv( $fh, array_keys( current( $data ) ) );

    foreach ( $data as $row ) {
        
        fputcsv( $fh, $row );
        
    }
    
    rewind( $fh );
    $csv = stream_get_contents( $fh );
    fclose( $fh );

    return $csv;
    
}

function do_import_export_page() { ?>
    
    <div class="wrap ots-admin-page">

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

            <div class="tabs-content">

                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=ots_export_team' ) ) ?>">

                    <h2><?php _e( 'Export', 'ots' ); ?></h2>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><?php _e( 'Export Team Members', 'ots' ) ?></th>
                                <td>
                                    <?php submit_button( __( 'Export', 'ots' ), 'secondary', 'ots-export-button', false, false ); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>

                <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=ots_import_team' ) ) ?>">

                    <h2><?php _e( 'Import', 'ots' ); ?></h2>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><?php _e( 'Import Team Members', 'ots' ) ?></th>
                                <td>
                                    <input type="file" name="ots_file_import"/>
                                    <?php submit_button( __( 'Import', 'ots' ), 'secondary', 'ots-export-button', false, false ); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>

            </div>


            <div class="clear"></div>

        </div>

    </div>
    
<?php }
