<?php

namespace ots;

function sanitize_checkbox( $value ) {

    if( $value != 'on' && $value != 'off' ) {
        return false;
    }

    return $value;

}


function do_select_box( array $args ) { ?>

    <select name="<?php esc_attr_e( $args['name'] ); ?>"

        <?php if( isset( $args['attrs'] ) ) : print_attrs( $args['attrs'] ); endif; ?> >

        <?php foreach( $args['options'] as $value => $label ) : ?>

            <option value="<?php esc_attr_e( $value ); ?>"
                <?php selected( isset( $args['selected'] ) ? $args['selected'] : '', $value ); ?>>

                <?php esc_html_e( $label ); ?></option>

        <?php endforeach; ?>

    </select>

    <?php if( isset( $args['description'] ) ) : ?>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>

    <?php endif; ?>

<?php }


function do_check_box( array $args ) { ?>

    <label>

        <input name="<?php esc_attr_e( $args['name'] ); ?>"
               type="checkbox"

            <?php if( isset( $args['attrs'] ) ) : print_attrs( $args['attrs'] ); endif; ?>

            <?php checked( 'on', $args['checked'] ); ?>/>

        <?php esc_html_e( $args['label'] ); ?>

    </label>

<?php }


function do_text_box( array $args ) { ?>

    <input name="<?php esc_attr_e( $args['name'] ); ?>"
           value="<?php echo isset( $args['value'] ) ? esc_attr( $args['value'] ) : ''; ?>"

        <?php if( isset( $args['attrs'] ) ) : print_attrs( $args['attrs'] ); endif; ?> />

    <?php if( isset( $args['description'] ) ) : ?>

        <p class="description"><?php esc_html_e( $args['description'] ); ?></p>

    <?php endif; ?>

<?php }


function do_pro_only_field() { ?>

    <p class="description"><?php _e( 'Pro version only', 'ots' ); ?></p>

<?php }


function print_attrs( array $attrs ) {

    foreach( $attrs as $attr => $values ) {
        echo ' ' . $attr . '="' . $values . '" ';
    }

}
