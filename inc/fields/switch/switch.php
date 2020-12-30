<?php

namespace MSPFramework;

class Field_switch extends Field {
    public static function enqueue_assets( $settings_page ) {
        $field_path = Utilities::get_dir_path( __DIR__ );
        $field_url  = Utilities::get_dir_url( __DIR__ );

        $min = $settings_page->config->debug_mod ? '.min' : '';

		wp_enqueue_style(
			'msp-switch-field',
			$field_url . "switch$min.css",
			array(),
			md5( filectime( $field_path . "switch$min.css" ) )
		);
		wp_enqueue_script(
			'msp-switch-field',
			$field_url . "switch$min.js",
			array( 'msp-jquery', 'mdc' ),
			md5( filectime( $field_path . "switch$min.js" ) ),
			true
		);
	}

	function render() {
		$options = $this->options;
		$input   = array(
			'id'       => $this->id,
			'disabled' => false,
			'label'    => __( 'Enable \\ Disable', 'msp-framework' ),
			'value'    => false,
			'default'  => false
		);
		if ( isset( $options[ 'disabled' ] ) && is_bool( $options[ 'disabled' ] ) ) {
			$input[ 'disabled' ] = $options[ 'disabled' ];
		}
		if ( isset( $options[ 'label' ] ) && is_string( $options[ 'label' ] ) ) {
			$input[ 'label' ] = $options[ 'label' ];
		}
		if ( is_bool( $this->value ) ) {
			$input[ 'value' ] = $this->value;
		}
		if ( is_bool( $this->default_value ) ) {
			$input[ 'default_value' ] = $this->default_value;
		} ?>
        <div class="switch-field-container"
             data-msp-field-id="<?php echo esc_attr( $this->id ); ?>"
             data-msp-field-section-id="<?php echo esc_attr( $this->section_id ); ?>"
             data-msp-field-default-value="<?php echo esc_attr( $input[ 'default_value' ] ); ?>">
            <div class="mdc-switch
                        <?php if ( $input[ 'disabled' ] ) { ?>mdc-switch--disabled<?php } ?>
                        <?php if ( $input[ 'value' ] ) { ?>mdc-switch--checked<?php } ?>">
                <div class="mdc-switch__track"></div>
                <div class="mdc-switch__thumb-underlay">
                    <div class="mdc-switch__thumb"></div>
                    <input type="checkbox"
                           class="mdc-switch__native-control"
                           id="<?php echo $this->get_field_id( 'input' ); ?>"
					       <?php if ( $input[ 'disabled' ] ) { ?>disabled="disabled"<?php } ?>
                           role="switch"
					       <?php if ( $input[ 'value' ] ) { ?>aria-checked="true" checked<?php } ?>
					       <?php if ( ! $input[ 'value' ] ) { ?>aria-checked="false"<?php } ?>>
                </div>
            </div>
            <label for="<?php echo $this->get_field_id( 'input' ); ?>">
				<?php echo esc_html( $input[ 'label' ] ); ?>
            </label>
        </div>
	<?php }

	function check_value( $value ) {
		return $value == true;
	}
}