<?php

namespace MSPFramework;

class Field_text extends Field implements ValidatableField {
    public static function enqueue_assets( $settings_page ) {
        $field_path = Utilities::get_dir_path( __DIR__ );
        $field_url  = Utilities::get_dir_url( __DIR__ );

        $min = $settings_page->config->debug_mod ? '' : '.min';

		wp_enqueue_style(
			'msp-text-field',
			$field_url . "text$min.css",
			array(),
			md5( filectime( $field_path . "text$min.css" ) )
		);
		wp_enqueue_script(
			'msp-text-field',
			$field_url . "text$min.js",
			array( 'msp-jquery', 'mdc' ),
			md5( filectime( $field_path . "text$min.js" ) ),
			true
		);
	}

	function render() {
		$options        = $this->options;
		$inputs         = array();
		$is_multi_input = false;
		if ( $this->is_multi_input() ) {
			$is_multi_input = true;
			foreach ( $options[ 'data' ] as $input_name ) {
				$input = array(
					'id'                        => $this->id,
					'input-name'                => $input_name,
					'disabled'                  => false,
					'max-characters'            => 0,
					'display-character-counter' => true,
					'leading-icon'              => null,
					'trailing-icon'             => null,
					'value'                     => '',
					'default_value'             => ''
				);
				if ( isset( $options[ 'label' ] ) ) {
					if ( is_array( $options[ 'label' ] ) &&
					     isset( $options[ 'label' ][ $input_name ] ) &&
					     is_string( $options[ 'label' ][ $input_name ] ) ) {
						$input[ 'label' ] = $options[ 'label' ][ $input_name ];
					} elseif ( is_string( $options[ 'label' ] ) ) {
						$input[ 'label' ] = $options[ 'label' ];
					}
				}
				if ( isset( $options[ 'disabled' ] ) ) {
					if ( is_array( $options[ 'disabled' ] ) &&
					     isset( $options[ 'disabled' ][ $input_name ] ) &&
					     is_bool( $options[ 'disabled' ][ $input_name ] ) ) {
						$input[ 'disabled' ] = $options[ 'disabled' ][ $input_name ];
					} elseif ( is_bool( $options[ 'disabled' ] ) ) {
						$input[ 'disabled' ] = $options[ 'disabled' ];
					}
				}
				if ( isset( $options[ 'max-characters' ] ) ) {
					if ( is_array( $options[ 'max-characters' ] ) &&
					     isset( $options[ 'max-characters' ][ $input_name ] ) &&
					     is_int( $options[ 'max-characters' ][ $input_name ] ) ) {
						$input[ 'max-characters' ] = $options[ 'max-characters' ][ $input_name ];
					} elseif ( is_int( $options[ 'max-characters' ] ) ) {
						$input[ 'max-characters' ] = $options[ 'max-characters' ];
					}
				}
				if ( isset( $options[ 'display-character-counter' ] ) ) {
					if ( is_array( $options[ 'display-character-counter' ] ) &&
					     isset( $options[ 'display-character-counter' ][ $input_name ] ) &&
					     is_bool( $options[ 'display-character-counter' ][ $input_name ] ) ) {
						$input[ 'display-character-counter' ] = $options[ 'display-character-counter' ][ $input_name ];
					} elseif ( is_bool( $options[ 'display-character-counter' ] ) ) {
						$input[ 'display-character-counter' ] = $options[ 'display-character-counter' ];
					}
				}
				if ( isset( $options[ 'prefix' ] ) ) {
					if ( is_array( $options[ 'prefix' ] ) &&
					     isset( $options[ 'prefix' ][ $input_name ] ) &&
					     is_string( $options[ 'prefix' ][ $input_name ] ) ) {
						$input[ 'prefix' ] = $options[ 'prefix' ][ $input_name ];
					} elseif ( is_string( $options[ 'prefix' ] ) ) {
						$input[ 'prefix' ] = $options[ 'prefix' ];
					}
				}
				if ( isset( $options[ 'suffix' ] ) ) {
					if ( is_array( $options[ 'suffix' ] ) &&
					     isset( $options[ 'suffix' ][ $input_name ] ) &&
					     is_string( $options[ 'suffix' ][ $input_name ] ) ) {
						$input[ 'suffix' ] = $options[ 'suffix' ][ $input_name ];
					} elseif ( is_string( $options[ 'suffix' ] ) ) {
						$input[ 'suffix' ] = $options[ 'suffix' ];
					}
				}
				if ( isset( $options[ 'leading-icon' ] ) ) {
					if ( is_array( $options[ 'leading-icon' ] ) &&
					     isset( $options[ 'leading-icon' ][ $input_name ] ) &&
					     $options[ 'leading-icon' ][ $input_name ] instanceof Icon ) {
						$input[ 'leading-icon' ] = $options[ 'leading-icon' ][ $input_name ];
					} elseif ( $options[ 'leading-icon' ] instanceof Icon ) {
						$input[ 'leading-icon' ] = $options[ 'leading-icon' ];
					}
				}
				if ( isset( $options[ 'trailing-icon' ] ) ) {
					if ( is_array( $options[ 'trailing-icon' ] ) &&
					     isset( $options[ 'trailing-icon' ][ $input_name ] ) &&
					     $options[ 'trailing-icon' ][ $input_name ] instanceof Icon ) {
						$input[ 'trailing-icon' ] = $options[ 'trailing-icon' ][ $input_name ];
					} elseif ( $options[ 'trailing-icon' ] instanceof Icon ) {
						$input[ 'trailing-icon' ] = $options[ 'trailing-icon' ];
					}
				}
				if ( is_array( $this->value ) &&
				     isset( $this->value[ $input_name ] ) &&
				     is_string( $this->value[ $input_name ] ) ) {
					$input[ 'value' ] = $this->value[ $input_name ];
				} elseif ( is_string( $this->value ) ) {
					$input[ 'value' ] = $this->value;
				}
				if ( is_array( $this->default_value ) &&
				     isset( $this->default_value[ $input_name ] ) &&
				     is_string( $this->default_value[ $input_name ] ) ) {
					$input[ 'default_value' ] = $this->default_value[ $input_name ];
				} elseif ( is_string( $this->default_value ) ) {
					$input[ 'default_value' ] = $this->default_value;
				}
				$inputs[ $input_name ] = $input;
			}
		} else {
			$options[ 'data' ] = array( 'text' );
			$input             = array(
				'id'                        => $this->id,
				'input-name'                => 'text',
				'disabled'                  => false,
				'max-characters'            => 0,
				'display-character-counter' => true,
				'leading-icon'              => null,
				'trailing-icon'             => null,
				'value'                     => '',
				'default_value'             => ''
			);
			if ( isset( $options[ 'label' ] ) && is_string( $options[ 'label' ] ) ) {
				$input[ 'label' ] = $options[ 'label' ];
			}
			if ( isset( $options[ 'disabled' ] ) && is_bool( $options[ 'disabled' ] ) ) {
				$input[ 'disabled' ] = $options[ 'disabled' ];
			}
			if ( isset( $options[ 'max-characters' ] ) && is_int( $options[ 'max-characters' ] ) ) {
				$input[ 'max-characters' ] = $options[ 'max-characters' ];
			}
			if ( isset( $options[ 'display-character-counter' ] ) && is_bool( $options[ 'display-character-counter' ] ) ) {
				$input[ 'display-character-counter' ] = $options[ 'display-character-counter' ];
			}
			if ( isset( $options[ 'prefix' ] ) && is_string( $options[ 'prefix' ] ) ) {
				$input[ 'prefix' ] = $options[ 'prefix' ];
			}
			if ( isset( $options[ 'suffix' ] ) && is_string( $options[ 'suffix' ] ) ) {
				$input[ 'suffix' ] = $options[ 'suffix' ];
			}
			if ( isset( $options[ 'leading-icon' ] ) && $options[ 'leading-icon' ] instanceof Icon ) {
				$input[ 'leading-icon' ] = $options[ 'leading-icon' ];
			}
			if ( isset( $options[ 'trailing-icon' ] ) && $options[ 'trailing-icon' ] instanceof Icon ) {
				$input[ 'trailing-icon' ] = $options[ 'trailing-icon' ];
			}
			if ( is_string( $this->value ) ) {
				$input[ 'value' ] = $this->value;
			}
			if ( is_string( $this->default_value ) ) {
				$input[ 'default_value' ] = $this->default_value;
			}
			$inputs[ $input[ 'input-name' ] ] = $input;
		} ?>
        <div class="text-field-inputs"
             data-msp-field-id="<?php echo esc_attr( $this->id ); ?>"
             data-msp-field-section-id="<?php echo esc_attr( $this->section_id ); ?>"
             data-msp-text-field-is-multi-input="<?php echo $is_multi_input ? 'true' : 'false'; ?>">
			<?php foreach ( $inputs as $input ) { ?>
                <label class="mdc-text-field mdc-text-field--outlined <?php if ( $input[ 'disabled' ] ) {
					?>mdc-text-field--disabled <?php }
				if ( ! isset( $input[ 'label' ] ) ) {
					?>mdc-text-field--no-label <?php }
				if ( $input[ 'leading-icon' ] != null ) {
					?>mdc-text-field--with-leading-icon <?php }
				if ( $input[ 'trailing-icon' ] != null ) {
					?>mdc-text-field--with-trailing-icon<?php } ?>"
                       data-msp-text-field-input-name="<?php echo esc_attr( $input[ 'input-name' ] ); ?>"
                       data-msp-text-field-input-default-value="<?php echo esc_attr( $input[ 'default_value' ] ); ?>"
                       style="width: 100%;">
					<?php if ( $input[ 'leading-icon' ] instanceof Icon ) {
						$input[ 'leading-icon' ]->render( array(
							'mdc-text-field__icon',
							'mdc-text-field__icon--leading'
						) );
					}
					if ( isset( $input[ 'prefix' ] ) ) { ?>
                        <span class="mdc-text-field__affix mdc-text-field__affix--prefix">
                            <?php echo esc_html( $input[ 'prefix' ] ); ?>
                        </span>
					<?php } ?>
                    <input type="text"
                           class="mdc-text-field__input"
					       <?php if ( $input[ 'disabled' ] ) { ?>disabled="disabled"<?php } ?>
                           name="<?php echo $this->get_field_name( $input[ 'input-name' ] ); ?>"
                           value="<?php echo esc_attr( $input[ 'value' ] ); ?>"
						<?php if ( isset( $input[ 'label' ] ) ) { ?>
                            aria-labelledby="<?php echo $this->get_field_id(
								$input[ 'input-name' ] . '-label'
							); ?>"
						<?php } ?>
						   <?php if ( $input[ 'max-characters' ] > 0 ) {
					       ?>maxlength="<?php echo $input[ 'max-characters' ]; ?>"<?php } ?>>
					<?php if ( isset( $input[ 'suffix' ] ) ) { ?>
                        <span class="mdc-text-field__affix mdc-text-field__affix--suffix">
                            <?php echo esc_html( $input[ 'suffix' ] ); ?>
                        </span>
					<?php }
					if ( $input[ 'trailing-icon' ] instanceof Icon ) {
						$input[ 'trailing-icon' ]->render( array(
							'mdc-text-field__icon',
							'mdc-text-field__icon--trailing'
						) );
					} ?>
                    <span class="mdc-notched-outline">
                        <span class="mdc-notched-outline__leading"></span>
                        <?php if ( isset( $input[ 'label' ] ) ) { ?>
                            <span class="mdc-notched-outline__notch">
                                <span class="mdc-floating-label"
                                      id="<?php echo $this->get_field_id(
	                                      $input[ 'input-name' ] . '-label'
                                      ); ?>">
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
			<?php } ?>
        </div>
	<?php }

	function is_multi_input() {
		return isset( $this->options[ 'data' ] ) && is_array( $this->options[ 'data' ] );
	}

	function validate( $value ) {
		$value          = $this->check_value( $value );
		$existing_value = $this->check_value( $this->value );
		if ( ! $this->hasValidator() ) {
			return array( 'value' => $value );
		}
		$validation = $this->options[ 'validation' ];
		if ( $this->is_multi_input() ) {
			$messages         = array();
			$validated_values = array();
			foreach ( $value as $input_name => $input_value ) {
				$validator = null;
				if ( is_array( $validation ) ) {
					if ( isset( $validation[ $input_name ] ) ) {
						$validator = $validation[ $input_name ];
					}
				} else if ( $validation instanceof Validator or is_string( $validation ) ) {
					$validator = $validation;
				}
				$validated_value = $input_value;
				if ( $validator instanceof Validator or is_string( $validator ) ) {
					$return          = Validation::validate( $validator, $input_value, $existing_value[ $input_name ], $this );
					$validated_value = $return[ 'value' ];
					if ( isset( $return[ 'message' ] ) ) {
						$messages[] = $return[ 'message' ];
					}
				}
				$validated_values[ $input_name ] = $validated_value;
			}
			$return = array( 'value' => $validated_values );
			if ( count( $messages ) > 0 ) {
				$return[ 'message' ] = join( $messages, '<br>' );
			}

			return $return;
		} else {
			return Validation::validate( $validation, $value, $existing_value, $this );
		}
	}

	function check_value( $value ) {
		if ( $this->is_multi_input() ) {
			if ( ! is_array( $value ) ) {
				$value = array();
				foreach ( $this->options[ 'data' ] as $input ) {
					$value[ $input ] = '';
				}
			}

			return $value;
		}

		return is_string( $value ) ? $value : '';
	}

	function hasValidator() {
		if ( ! isset( $this->options[ 'validation' ] ) ) {
			return false;
		}
		$validation = $this->options[ 'validation' ];
		if ( $validation instanceof Validator or is_string( $validation ) ) {
			return true;
		}

		return $this->is_multi_input() ? is_array( $validation ) : false;
	}
}