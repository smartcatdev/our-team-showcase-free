<?php

namespace ots;

function get_csv_fields() {
    $sanitize_bool = function ( $val ) {
        return $val === 'on' || empty( $val ) ? $val : '';
    };
    $fields = array(
        'name'          => 'sanitize_text_field',
        'title'         => 'sanitize_text_field',
        'bio'           => 'wp_kses_post',
        'photo_url'     => 'esc_url_raw',
        'email'         => 'sanitize_email',
        'phone'         => 'sanitize_text_field',
        'facebook'      => 'esc_url_raw',
        'twitter'       => 'esc_url_raw',
        'linkedin'      => 'esc_url_raw',
        'instagram'     => 'esc_url_raw',
        'pinterest'     => 'esc_url_raw',
        'gplus'         => 'esc_url_raw',
        'website'       => 'esc_url_raw',
        'other_icon'    => 'sanitize_text_field',
        'other'         => 'sanitize_text_field',
        'article_bool'  => $sanitize_bool,
        'article_title' => 'sanitize_text_field',
        'article1'      => 'intval',
        'article2'      => 'intval',
        'article3'      => 'intval',
        'skill_bool'    => $sanitize_bool,
        'skill_title'   => 'sanitize_text_field',
        'skill1'        => 'sanitize_text_field',
        'skill_value1'  => 'intval',
        'skill2'        => 'sanitize_text_field',
        'skill_value2'  => 'intval',
        'skill3'        => 'sanitize_text_field',
        'skill_value3'  => 'intval',
        'skill4'        => 'sanitize_text_field',
        'skill_value4'  => 'intval',
        'skill5'        => 'sanitize_text_field',
        'skill_value5'  => 'intval',
        'tags_bool'     => $sanitize_bool,
        'tags_title'    => 'sanitize_text_field',
        'tags'          => 'sanitize_text_field',
        'quote'         => 'sanitize_text_field'
    );
    return $fields;
}

add_action( 'admin_post_ots_export_team', function() {
    $message = array();
    $team    = new \WP_Query( array(
        'post_type'         => 'team_member',
        'post_status'       => 'publish',
        'posts_per_page'    => -1
    ) );
    
    if ( $team->post_count <= 0 ) {
        $message['export_response'] = 'Failed';
        $message['export_output'] = urlencode( 'There are no team members to export' );
    } else {
        $members = array();
        
        foreach( $team->posts as $member ) {
            $member = team_member( $member->ID );
            $export = array(
                'name'      => sanitize_text_field( $member->get_name() ),
                'title'     => sanitize_text_field( $member->title ),
                'bio'       => wp_kses_post( $member->get_bio() ),
                'photo_url' => esc_url_raw( get_the_post_thumbnail_url( $member->get_id() ) ),
            );

            $fields = get_csv_fields();

            unset( $fields['name'] );
            unset( $fields['title'] );
            unset( $fields['bio'] );
            unset( $fields['photo_url'] );

            foreach ( $fields as $field => $sanitize_callback ) {
                $export[$field] = call_user_func( $sanitize_callback, $member->{$field} );
            }

            $members[ $member->get_id() ] = $export;
        }

        $csv = put_csv( $members );
        $dir = wp_upload_dir();
        $file_path = $dir['path'] . '/export.csv';

        if ( create_file( $file_path, $csv ) ) {
            $message['export_response'] = 'Success!';
            $message['export_output'] = $dir['url'] . '/export.csv';
        } else {
            $message['export_response'] = 'Failed';
            $message['export_output'] = urlencode( 'Cannot create file, check your server permissions' );
            $message['csv'] = $csv;
        }
    }
    
    wp_safe_redirect( add_query_arg( $message, wp_get_referer() ) );
});


add_action( 'admin_post_ots_import_team', function() {
    $message = array();
    $total_imported = 0;
    
    maybe_delete_members();

//    $accepted_mime_types = array(
//        'text/csv',
//        'text/comma-separated-values',
//        'text/plain',
//        'text/anytext',
//        'text/*',
//        'text/plain',
//        'text/anytext',
//        'text/*',
//        'application/csv',
//        'application/excel',
//    );
    
    // Ensure user has selected an import file
    if ( empty( $_FILES['ots_file_import'] ) ) {
        $message['import_response'] = 'Failed';
        $message['import_output'] = urlencode( 'Missing import file.' );
        wp_safe_redirect( add_query_arg( $message, wp_get_referer() ) );
        return;
    }
    
    // Ensure the file is of the right type
//    if ( empty( $_FILES['ots_file_import']['type'] ) || !in_array( strtolower( $_FILES['ots_file_import']['type'] ), $accepted_mime_types ) ) {
//        $message['import_response'] = 'Failed';
//        $message['import_output'] = urlencode( 'The file you have uploaded cannot be processed. Please upload a CSV file.' );
//        wp_safe_redirect( add_query_arg( $message, wp_get_referer() ) );
//        return;
//    }
    
    $upload = wp_upload_bits( $_FILES['ots_file_import']['name'], null, file_get_contents( $_FILES['ots_file_import']['tmp_name'] ) );
    $data = read_csv( $upload['file'] );
    
    foreach ( $data as $row ) {
        $id = wp_insert_post( array(
            'post_type'     => 'team_member',
            'post_status'   => 'publish'
        ) );

        if ( is_wp_error( $id ) ) {
            continue;
        }

        $member = team_member( $id );
        $fields = get_csv_fields();
        $total_imported++;

        foreach ( $row as $key => $val ) {
            $val = call_user_func( $fields[$key], $val );

            switch( $key ) {
                case 'name' : 
                    $member->set_name( $val );
                    break;
                
                case 'photo_url' :
                    import_photo( $member, $val );
                    break;
                
                case 'bio' :
                    $member->set_bio( $val );
                    break;
                
                default :
                    $member->$key = $val;
                    break;
            }
        }
    }
    
    $message['import_response'] = 'Success';
    $message['import_output'] = urlencode( 'Import successful! ' . $total_imported . ' member(s) imported' );

    wp_safe_redirect( add_query_arg( $message, wp_get_referer() ) );
});


function import_photo( TeamMember $member, $photo_url ) {
    if ( !$photo_url ) {
        return;
    }

    $attach_id = media_sideload_image( $photo_url, $member->get_id(), null, 'id' );

    if ( !is_wp_error( $attach_id ) ) {
        update_post_meta( $member->get_id(), '_thumbnail_id', $attach_id );
    }
}

function get_mime_type( $extension ) {
    $mime = null;
    
    switch ( $extension ) {
        case 'jpg' || 'jpeg' :
            $mime = 'image/jpeg';
            break;
        
        case 'png' :
            $mime = 'image/png';
            break;
        
        default :
            $mime = 'image/jpeg';
            break;
    }
    
    return $mime;
}

/**
 * 
 * Creates a file at the specified location
 * 
 * @param String $file_path
 * @param String $contents
 * @param String $mode
 *
 * @return boolean
 */
function create_file( $file_path, $contents, $mode = 'w' ) {
    $file = fopen( $file_path, $mode );
    
    if ( !$file ) {
        return false;
    }
    return fwrite( $file, $contents ) && fclose( $file );
}

function maybe_delete_members() {
    if ( !empty( $_POST['ots_delete_existing'] ) ) {
        $team = new \WP_Query( array(
            'post_type'      => 'team_member',
            'post_status'    => 'publish',
            'posts_per_page' => -1
        ) );

        foreach( $team->posts as $member ) {
            wp_delete_post( $member->ID );
        }
    }
}

function read_csv( $file_path, $mode = 'r' ) {
    $array  = array();
    $fields = array();
    $i      = 0;
    
    $handle = fopen( $file_path, $mode );
    
    while ( ( $row = fgetcsv( $handle, 4096 ) ) !== false ) {
        if ( empty( $fields ) ) {
            $fields = $row;
            continue;
        }
        
        foreach ( $row as $k => $value ) {
            $array[$i][$fields[$k]] = $value;
        }
        
        $i++;
    }
    
    if ( !feof( $handle ) ) {
        echo "Error: unexpected fgets() fail\n";
    }
    
    fclose( $handle );
    
    return $array;
}

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

            <div class="tabs-content" style="width: 75%">

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
                            
                            <?php $response = isset( $_GET['export_response'] ) ? $_GET['export_response'] : false; ?>
                            <?php if( $response ) : ?> 
                            <tr class="tool-status-<?php echo esc_attr( strtolower( $response ) ); ?>">
                                <th><?php echo esc_attr( $response ); ?></th>
                                <td>
                                    <?php if( $response == 'Failed' ) : ?>
                                        <?php echo $_GET['export_output']; ?>
                                    <?php else : ?>
                                            <a href="<?php echo esc_url( $_GET['export_output'] ) ?>" class="button button-primary"><?php _e( 'Download', 'ots' ); ?></a>
                                    <?php endif; ?>
                                    

                                </td>
                            </tr>
                            <?php endif; ?>
                            
                            
                        </tbody>
                    </table>
                </form>

                <form method="post" id="ots-import-form" action="<?php echo esc_url( admin_url( 'admin-post.php?action=ots_import_team' ) ) ?>" enctype="multipart/form-data">

                    <h2><?php _e( 'Import', 'ots' ); ?></h2>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><?php _e( 'Import Team Members', 'ots' ) ?></th>
                                <td>
                                    <input type="file" name="ots_file_import"/><br><br>
                                    <label>
                                        <input type="checkbox" name="ots_delete_existing" id="ots-import-replace-existing"/>
                                        <?php _e( 'Delete existing ?', 'ots' ); ?></label><br><br>
                                    <?php submit_button( __( 'Import', 'ots' ), 'primary', 'ots-import--button', false, false ); ?>
                                </td>
                            </tr>
                            
                            <?php $response = isset( $_GET['import_response'] ) ? $_GET['import_response'] : false; ?>
                            <?php if( $response ) : ?> 
                            
                            <tr class="tool-status-<?php echo esc_attr( strtolower( $response ) ); ?>">
                                <th><?php echo $response; ?></th>
                                <td><?php echo $_GET[ 'import_output' ] ?: ''; ?></td>
                            </tr>
                            
                            <?php endif; ?>
                            
                            
                            <tr>
                                <td colspan="2">
                                    <a href="<?php echo admin_url( 'edit.php?post_type=team_member&page=ots-docs&tab=ots-import-export' ); ?>"><?php _e( 'Click here', 'ots' ); ?></a> <?php _e( 'to learn more about uploading members and CSV file structure', 'ots' ) ?>
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
