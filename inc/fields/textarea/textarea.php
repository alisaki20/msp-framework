<?php

namespace MSPFramework;

class Field_textarea extends Field {
    public static function enqueue_assets( $settings_page ) {
        $field_path = Utilities::get_dir_path( __DIR__ );
        $field_url  = Utilities::get_dir_url( __DIR__ );

        $min = $settings_page->config->debug_mod ? '' : '.min';

		wp_enqueue_style(
			'msp-textarea-field',
			$field_url . "textarea$min.css",
			array(),
			md5( filectime( $field_path . "textarea$min.css" ) )
		);
		wp_enqueue_script(
			'msp-textarea-field',
			$field_url . "textarea$min.js",
			array( 'msp-jquery', 'mdc' ),
			md5( filectime( $field_path . "textarea$min.js" ) ),
			true
		);
	}


	function render() {
		$options = $this->options;
		$input   = array(
			'id'                        => $this->id,
			'disabled'                  => false,
			'visible-rows'              => 8,
			'max-characters'            => 0,
			'display-character-counter' => true,
			'value'                     => '',
			'default_value'             => ''
		);
		if ( isset( $options[ 'disabled' ] ) && is_bool( $options[ 'disabled' ] ) ) {
			$input[ 'disabled' ] = $options[ 'disabled' ];
		}
		if ( isset( $options[ 'visible-rows' ] ) && is_int( $options[ 'visible-rows' ] ) ) {
			$input[ 'visible-rows' ] = $options[ 'visible-rows' ];
		}
		if ( isset( $options[ 'max-characters' ] ) && is_int( $options[ 'max-characters' ] ) ) {
			$input[ 'max-characters' ] = $options[ 'max-characters' ];
		}
		if ( isset( $options[ 'display-character-counter' ] ) && is_bool( $options[ 'display-character-counter' ] ) ) {
			$input[ 'display-character-counter' ] = $options[ 'display-character-counter' ];
		}
		if ( isset( $options[ 'label' ] ) && is_string( $options[ 'label' ] ) ) {
			$input[ 'label' ] = $options[ 'label' ];
		}
		if ( is_string( $this->value ) ) {
			$input[ 'value' ] = $this->value;
		}
		if ( is_string( $this->default_value ) ) {
			$input[ 'default_value' ] = $this->default_value;
		}
		?>
        <div class="textarea-field-container"
             data-msp-field-id="<?php echo esc_attr( $this->id ); ?>"
             data-msp-field-section-id="<?php echo esc_attr( $this->section_id ); ?>"
             data-msp-textarea-field-default-value="<?php echo esc_attr( $input[ 'default_value' ] ); ?>">
            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea mdc-text-field--no-label
	                mdc-text-field--fullwidth
	                <?php if ( $input[ 'disabled' ] ) { ?>mdc-text-field--disabled<?php } ?>
	                <?php if ( ! isset( $input[ 'label' ] ) ) { ?>mdc-text-field--no-label<?php } ?>">
				<span class="mdc-text-field__resizer">
					<textarea class="mdc-text-field__input"
                              rows="<?php echo $input[ 'visible-rows' ] ?>"
                              name="<?php echo $this->get_field_name(); ?>"
					          <?php if ( isset( $input[ 'label' ] ) ) { ?>aria-labelledby="<?php
					          echo $this->get_field_id( 'label' ) ?>" <?php }
					          if ( $input[ 'disabled' ] ) { ?>disabled="disabled" <?php }
					if ( $input[ 'max-characters' ] > 0 ) { ?>
                        maxlength="<?php echo $input[ 'max-characters' ]; ?>"
					<?php } ?>><?php echo esc_html( $input[ 'value' ] ); ?></textarea>
				</span>
                <span class="mdc-notched-outline">
					<span class="mdc-notched-outline__leading"></span>
                    <?php if ( isset( $input[ 'label' ] ) ) { ?>
                        <span class="mdc-notched-outline__notch">
                            <span class="mdc-floating-label"
                                  id="<?php echo $this->get_field_id( 'label' ) ?>">
                                <?php echo esc_html( $input[ 'label' ] ); ?>
                            </span>
                        </span>
                    <?php } ?>
					<span class="mdc-notched-outline__trailing"></span>
				</span>
            </label>
			<?php if ( $input[ 'max-characters' ] > 0 && $input[ 'display-character-counter' ] ) { ?>
                <div class="mdc-text-field-helper-line">
					<span class="mdc-text-field-character-counter">
						0 / <?php echo $input[ 'max-characters' ]; ?>
					</span>
                </div>
			<?php } ?>
        </div>
	<?php }

	function check_value( $value ) {
		return is_string( $value ) ? $value : '';
	}
}