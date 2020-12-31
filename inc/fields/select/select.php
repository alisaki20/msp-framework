<?php

namespace MSPFramework;

class Field_select extends Field {
    public static function enqueue_assets( $settings_page ) {
        $field_path = Utilities::get_dir_path( __DIR__ );
        $field_url  = Utilities::get_dir_url( __DIR__ );

        $min = $settings_page->config->debug_mod ? '' : '.min';

		wp_enqueue_style(
			'msp-select-field',
			$field_url . "select$min.css",
			array(),
			md5( filectime( $field_path . "select$min.css" ) )
		);
		wp_enqueue_script(
			'msp-select-field',
			$field_url . "select$min.js",
			array( 'msp-jquery', 'mdc' ),
			md5( filectime( $field_path . "select$min.js" ) ),
			true
		);
	}

	function render() {
		$options = $this->options;
		$input   = array(
			'id'            => $this->id,
			'disabled'      => false,
			'leading-icon'  => null,
			'value'         => '',
			'default_value' => ''
		);
		if ( isset( $options[ 'disabled' ] ) && is_bool( $options[ 'disabled' ] ) ) {
			$input[ 'disabled' ] = $options[ 'disabled' ];
		}
		if ( isset( $options[ 'label' ] ) && is_string( $options[ 'label' ] ) ) {
			$input[ 'label' ] = $options[ 'label' ];
		}
		if ( isset( $options[ 'leading-icon' ] ) && $options[ 'leading-icon' ] instanceof Icon ) {
			$input[ 'leading-icon' ] = $options[ 'leading-icon' ];
		}
		if ( is_string( $this->value ) ) {
			$input[ 'value' ] = $this->value;
		}
		$input[ 'options' ] = array();
		if ( isset( $options[ 'options' ] ) && is_array( $options[ 'options' ] ) ) {
			foreach ( $options[ 'options' ] as $key => $name ) {
				$input[ 'options' ][ $key ] = array(
					'name'       => $name,
					'attributes' => array( 'data-value' => $key, 'role' => 'option' ),
					'classes'    => array( 'mdc-list-item' )
				);
				if ( $key == $this->value ) {
					$input[ 'options' ][ $key ][ 'attributes' ][ 'aria-selected' ] = 'true';
					$input[ 'options' ][ $key ][ 'classes' ][]                     = 'mdc-list-item--selected';
				}
				if ( isset( $options[ 'disabled-options' ] ) && is_array( $options[ 'disabled-options' ] ) &&
				     isset( $options[ 'disabled-options' ][ $key ] ) && is_bool( $options[ 'disabled-options' ][ $key ] ) &&
				     $options[ 'disabled-options' ][ $key ] ) {
					$input[ 'options' ][ $key ][ 'attributes' ][ 'aria-disabled' ] = 'true';
					$input[ 'options' ][ $key ][ 'classes' ][]                     = 'mdc-list-item--disabled';
				}
			}
		}
		if ( is_string( $this->default_value ) ) {
			$input[ 'default_value' ] = $this->default_value;
		} ?>
        <div class="select-field-container"
             data-msp-field-id="<?php echo esc_attr( $this->id ); ?>"
             data-msp-field-section-id="<?php echo esc_attr( $this->section_id ); ?>"
             data-msp-select-field-default-value="<?php echo esc_attr( $input[ 'default_value' ] ); ?>">
            <div class="mdc-select mdc-select--outlined <?php if ( $input[ 'disabled' ] ) {
				?>mdc-select--disabled <?php }
			if ( $input[ 'leading-icon' ] != null ) {
				?>mdc-select--with-leading-icon<?php } ?>">
                <div class="mdc-select__anchor"
                     aria-labelledby="<?php echo $this->get_field_id( 'label' ) ?>">
					<?php if ( $input[ 'leading-icon' ] instanceof Icon ) {
						$input[ 'leading-icon' ]->render( array( 'mdc-select__icon' ) );
					} ?>
                    <span class="mdc-select__selected-text"<?php if ( $input[ 'disabled' ] ) { ?>
                        aria-disabled="true"<?php } ?>><?php
						if ( isset( $input[ 'options' ][ $this->value ] ) ) {
							echo esc_html( $input[ 'options' ][ $this->value ][ 'name' ] );
						} ?></span>
                    <span class="mdc-select__dropdown-icon">
						<svg class="mdc-select__dropdown-icon-graphic" xmlns="http://www.w3.org/2000/svg"
                             viewBox="7 10 10 5">
							<polygon class="mdc-select__dropdown-icon-inactive"
                                     stroke="none"
                                     fill-rule="evenodd"
                                     points="7 10 12 15 17 10">
							</polygon>
							<polygon class="mdc-select__dropdown-icon-active"
                                     stroke="none"
                                     fill-rule="evenodd"
                                     points="7 15 12 10 17 15">
							</polygon>
						</svg>
					</span>
                    <span class="mdc-notched-outline">
						<span class="mdc-notched-outline__leading"></span>
                        <?php if ( isset( $input[ 'label' ] ) ) { ?><span class="mdc-notched-outline__notch">
                            <span id="<?php echo $this->get_field_id( 'label' ); ?>"
                                  class="mdc-floating-label"><?php echo esc_html( $input[ 'label' ] ); ?></span>
                            </span>
                        <?php } ?><span class="mdc-notched-outline__trailing"></span>
					</span>
                </div>

                <div class="mdc-select__menu mdc-menu mdc-menu-surface mdc-menu-surface--fullwidth" role="listbox">
                    <ul class="mdc-list">
						<?php foreach ( $input[ 'options' ] as $option ) {
							?>
                            <li class="<?php echo esc_attr( implode( ' ', $option[ 'classes' ] ) ); ?>"<?php
							foreach ( $option[ 'attributes' ] as $attribute => $value ) {
								echo ' ' . $attribute . '="' . esc_attr( $value ) . '"';
							} ?>>
                                <span class="mdc-list-item__ripple"></span>
								<?php if ( $input[ 'leading-icon' ] != null ) { ?>
                                    <span class="mdc-list-item__graphic"></span>
								<?php } ?>
                                <span class="mdc-list-item__text"><?php echo esc_html( $option[ 'name' ] ); ?></span>
                            </li>
						<?php } ?>
                    </ul>
                </div>
            </div>
        </div>
	<?php }

	function check_value( $value ) {
		return is_string( $value ) ? $value : '';
	}
}