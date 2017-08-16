<?php


namespace ots;

/**
 * Helper class for interacting with team members.
 *
 * @package ots
 * @since 4.0.0
 */
class TeamMember {

    protected $post;

    protected static $prefix = 'team_member_';


    public function __construct( \WP_Post $member ) {
        $this->post = $member;
    }

    public function __get( $key ) {
        return $this->get_metadata( $key );
    }

    public function __set( $key, $value ) {
        $this->set_metadata( $key, $value );
    }

    public function __isset( $key ) {

    	$meta = get_post_meta( $this->post->ID, $this->prefix( $key ) );

        return !empty( $meta );

    }

    public function __unset( $key ) {
        return delete_post_meta( $this->post->ID, $this->prefix( $key ) );
    }

    public function get_metadata( $key, $default = '', $single = true ) {
        $meta = get_post_meta( $this->post->ID, $this->prefix( $key ), $single );

        return !empty( $meta ) ? $meta : $default;

    }

    public function set_metadata( $key, $val = '', $unique = false ) {

        $key = $this->prefix( $key );

        if( !metadata_exists( 'post', $this->post->ID, $key ) ) {
            return add_post_meta( $this->post->ID, $key, $val, $unique );
        } else {
            return update_post_meta( $this->post->ID, $key, $val );
        }

    }

    protected function prefix( $key ) {
        return self::$prefix . $key;
    }

}