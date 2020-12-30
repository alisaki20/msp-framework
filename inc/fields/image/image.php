<?php

namespace MSPFramework;

class Field_image extends Field {
    public static function enqueue_assets( $settings_page ) {
        $field_path = Utilities::get_dir_path( __DIR__ );
        $field_url  = Utilities::get_dir_url( __DIR__ );

        $min = $settings_page->config->debug_mod ? '.min' : '';

		wp_enqueue_style(
			'msp-image-field',
			$field_url . "image$min.css",
			array(),
			md5( filectime( $field_path . "image$min.css" ) )
		);
		wp_enqueue_script(
			'msp-image-field',
			$field_url . "image$min.js",
			array( 'msp-jquery', 'mdc' ),
			md5( filectime( $field_path . "image$min.js" ) ),
			true
		);
	}

	function render() {
		$options = $this->options;
		$input   = array(
			'id'            => $this->id,
			'disabled'      => false,
			'value'         => array(),
			'default_value' => array()
		);
		if ( isset( $options[ 'disabled' ] ) && is_bool( $options[ 'disabled' ] ) ) {
			$input[ 'disabled' ] = $options[ 'disabled' ];
		}
		if ( is_array( $this->value ) ) {
			$input[ 'value' ] = $this->value;
		}
		if ( is_array( $this->default_value ) ) {
			$input[ 'default_value' ] = $this->default_value;
		}
		?>
    <div class="image-field-container mdc-elevation--z8"
         data-msp-field-id="<?php echo esc_attr( $this->id ); ?>"
         data-msp-field-section-id="<?php echo esc_attr( $this->section_id ); ?>">
        <input class="image-field-input" type="hidden" name="<?php echo $this->get_field_name(); ?>"
               value="<?php echo esc_attr( json_encode( $input[ 'value' ] ) ); ?>">
        <input class="image-field-input-default" type="hidden"
               value="<?php echo esc_attr( json_encode( $input[ 'default_value' ] ) ); ?>">
        <div class="image-field-content">
            <div class="image-field-content-inner">
                <div class="image-field-content-image<?php if ( count( $input[ 'value' ] ) == 0 ) {
					?> image-field-hidden<?php } ?>">
					<?php if ( count( $input[ 'value' ] ) > 0 ) { ?>
                        <img <?php if ( isset( $input[ 'value' ][ 'url' ] ) ) {
						     ?>src="<?php echo esc_url( $input[ 'value' ][ 'url' ] ); ?>" <?php }
						     if ( isset( $input[ 'value' ][ 'alt' ] ) ) {
						     ?>alt="<?php echo esc_attr( $input[ 'value' ][ 'alt' ] ); ?>" <?php }
						     if ( isset( $input[ 'value' ][ 'title' ] ) ) {
						     ?>title="<?php echo esc_attr( $input[ 'value' ][ 'title' ] ); ?>"<?php } ?>>
					<?php } ?>
                </div>
                <div class="image-field-content-no-image<?php if ( count( $input[ 'value' ] ) > 0 ) {
					?> image-field-hidden<?php } ?>">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="48px"
                             height="48px">
                            <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1
                                     0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/>
                        </svg>
                        <h4><?php esc_html_e( 'No Image Selected', 'msp-framework' ); ?></h4>
                    </div>
                    <p><?php esc_html_e( 'There is no select image.' .
					                     ' Please select image from Wordpress media library',
							'msp-framework'
						); ?></p>
                </div>
            </div>
        </div>
		<?php if ( ! $input[ 'disabled' ] ) { ?>
            <div class="image-field-buttons">
				<?php if ( count( $input[ 'default_value' ] ) > 0 ) { ?>
                    <button class="mdc-button mdc-button--outlined image-field-button-default<?php
					if ( count( $input[ 'value' ] ) > 0 ) { ?> image-field-hidden<?php } ?>">
                        <span class="mdc-button__ripple"></span>
						<?php esc_html_e( 'Default Image', 'msp-framework' ); ?>
                    </button>
				<?php } ?>
                <button class="mdc-button mdc-button--unelevated image-field-button-select<?php
				if ( count( $input[ 'value' ] ) > 0 ) { ?> image-field-hidden<?php } ?>">
                    <span class="mdc-button__ripple"></span>
					<?php esc_html_e( 'Select Image', 'msp-framework' ); ?>
                </button>
                <button class="mdc-button mdc-button--unelevated image-field-button-delete<?php
				if ( count( $input[ 'value' ] ) == 0 ) { ?> image-field-hidden<?php } ?>">
                    <span class="mdc-button__ripple"></span>
					<?php esc_html_e( 'Delete Image', 'msp-framework' ); ?>
                </button>
            </div>
            </div>
		<?php }
	}

	function check_value( $value ) {
		return is_array( $value ) ? $value : array();
	}
}