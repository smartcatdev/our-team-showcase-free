<?php

namespace ots;

add_action( 'admin_post_ots_export_team', function() {
    
    $message = array();
    
    $team = new \WP_Query( array(
        'post_type'     => 'team_member',
        'post_status'   => 'publish'
    ) );
    
    
    if( $team->post_count <= 0 ) {
        
        $message['export_response'] = 'Failed';
        $message['export_output'] = urlencode( 'There are no team members to export' );
        
    }else {
        
        
        $members = array();
        
        foreach( $team->posts as $member ) {
            
            $member = team_member( $member->ID );
            
            $members[ $member->get_id() ] = array(
                'name'              => $member->get_name(),
                'title'             => $member->title,
                'bio'               => $member->get_bio(),
                'photo_url'         => get_the_post_thumbnail_url( $member->get_id() ),
                'email'             => $member->email,
                'phone'             => $member->phone,
                'facebook'          => $member->facebook,
                'twitter'           => $member->twitter,
                'linkedin'          => $member->linkedin,
                'instagram'         => $member->instagram,
                'pinterest'         => $member->pinterest,
                'gplus'             => $member->gplus,
                'website'           => $member->website,
                'other_icon'        => $member->other_icon,
                'other'             => $member->other,
                'article_bool'      => $member->article_bool,
                'article_title'     => $member->article_title,
                'article1'          => $member->article1,
                'article2'          => $member->article2,
                'article3'          => $member->article3,
                'skill_bool'        => $member->skill_bool,
                'skill_title'       => $member->skill_title,
                'skill1'            => $member->skill1,
                'skill_value1'      => $member->skill_value1,
                'skill2'            => $member->skill2,
                'skill_value2'      => $member->skill_value2,
                'skill3'            => $member->skill3,
                'skill_value3'      => $member->skill_value3,
                'skill4'            => $member->skill4,
                'skill_value4'      => $member->skill_value4,
                'skill5'            => $member->skill5,
                'skill_value5'      => $member->skill_value5,
                'tags_bool'         => $member->tags_bool,
                'tags_title'        => $member->tags_title,
                'tags'              => $member->tags,
                'quote'             => $member->quote,
            );
            
        }

        $csv = put_csv( $members );
        
        $dir = wp_upload_dir();
        $file_path = $dir['path'] . '/export.csv';
        

        
        if( create_file( $file_path, $csv ) ) {

            $message['export_response'] = 'Success!';
            $message['export_output'] = $dir['url'] . '/export.csv';
            
        }else {

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
    
    $accepted_mime_types = array(
            'text/csv',
            'text/comma-separated-values',
            'text/plain',
            'text/anytext',
            'text/*',
            'text/plain',
            'text/anytext',
            'text/*',
            'application/csv',
            'application/excel',
    );
    
    // Ensure user has selected an import file
    if( empty( $_FILES['ots_file_import'] ) ) {
        
        $message['import_response'] = 'Failed';
        $message['import_output'] = urlencode( 'Missing import file.' );
        wp_safe_redirect( add_query_arg( $message, wp_get_referer() ) );
        return;
        
    }
    
    // Ensure the file is of the right type
    if( empty( $_FILES['ots_file_import']['type'] ) || ! in_array( strtolower( $_FILES['ots_file_import']['type'] ), $accepted_mime_types ) ) {
        
        $message['import_response'] = 'Failed';
        $message['import_output'] = urlencode( 'The file you have uploaded cannot be processed. Please upload a CSV file.' );
        wp_safe_redirect( add_query_arg( $message, wp_get_referer() ) );
        return;
        
    }
    
    $upload = wp_upload_bits( $_FILES['ots_file_import']['name'], null, file_get_contents( $_FILES['ots_file_import']['tmp_name'] ) );
    
    $data = read_csv( $upload['file'] );
    
    
    foreach( $data as $row ) {
                    
        try {

            $id = wp_insert_post( array(
                'post_type'     => 'team_member',
                'post_status'   => 'publish'
            ) );

        } catch ( \Exception $ex ) { 

            continue;

        }

        $member = team_member( $id );
        $total_imported++;

        foreach( $row as $key => $val ) {

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
    
    if( ! $photo_url ) {
        return;
    }
    
    $dir = wp_upload_dir();
    
    $contents = file_get_contents( $photo_url );
    $extension = substr( strrchr( $photo_url,'.' ), 1 );
    $uploadfile = $dir['path'] . '/' . $member->get_id() . '.' . $extension;

    $savefile = fopen( $uploadfile, 'w' );
    fwrite( $savefile, $contents );
    fclose( $savefile );
    
    $attachment = array(
        'post_title'        => $member->get_id() . '.' . $extension,
        'post_content'      => '',
        'post_mime_type'    => get_mime_type( $extension ),
        'post_status'       => 'publish',
        'post_parent'       => $member->get_id()
    );

    $attach_id = wp_insert_attachment( $attachment, $uploadfile, $member->get_id() );

    $attach_data = wp_generate_attachment_metadata( $attach_id, $uploadfile );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    update_post_meta( $member->get_id(), '_thumbnail_id', $attach_id );
    
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
 * @return boolean
 * @throws Exception
 */
function create_file( $file_path, $contents, $mode = 'w' ) {
    
    $file = fopen( $file_path, $mode );
    
    if( ! $file ) {
        return false;
    }
    
    fputs( $file, $contents );
    fclose( $file );
    
    return true;
    
}

function maybe_delete_members() {
    
    if( isset( $_POST['ots-import-replace-button'] ) ) {
        
        $team = new \WP_Query( array(
            'post_type'     => 'team_member'
        ) );


        if( $team->post_count > 0 ) {
            
            foreach( $team->posts as $member ) {
                wp_delete_post( $member->ID );
            }

        }
        
    }
    
    
    
}

function read_csv( $file_path, $mode = 'r' ) {
    
    $array = array();
    $fields = array();
    $i=0;
    
    $handle = fopen( $file_path, $mode );
    
    while ( ( $row = fgetcsv( $handle, 4096 ) ) !== false) {
        
        if ( empty( $fields ) ) {
            $fields = $row;
            continue;
        }
        
        foreach ($row as $k => $value ) {
            
            $array[$i][$fields[$k]] = $value;
            
        }
        
        $i++;
    }
    
    if ( !feof( $handle ) ) {
        
        echo "Error: unexpected fgets() fail\n";
        
    }
    
    fclose($handle);
    
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
                                    <?php submit_button( __( 'Import', 'ots' ), 'secondary', 'ots-import-button', false, false ); ?>
                                    <?php submit_button( __( 'Import & Replace Existing', 'ots' ), 'primary', 'ots-import-replace-button', false, false ); ?>
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
