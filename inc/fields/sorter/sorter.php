<?php

namespace MSPFramework;

class Field_sorter extends Field {
    public static function enqueue_assets( $settings_page ) {
        $field_path = Utilities::get_dir_path( __DIR__ );
        $field_url  = Utilities::get_dir_url( __DIR__ );

        $min = $settings_page->config->debug_mod ? '' : '.min';

		wp_enqueue_style(
			'msp-jquery-ui-sortable',
			$field_url . "jquery-ui-sortable$min.css",
			array(),
			md5( filectime( $field_path . "jquery-ui-sortable$min.css" ) )
		);
		wp_enqueue_script(
			'msp-jquery-ui-sortable',
			$field_url . "jquery-ui-sortable$min.js",
			array( 'msp-jquery' ),
			md5( filectime( $field_path . "jquery-ui-sortable$min.js" ) ),
			true
		);
		wp_enqueue_style(
			'msp-sorter-field',
			$field_url . "sorter$min.css",
			array(),
			md5( filectime( $field_path . "sorter$min.css" ) )
		);
		wp_enqueue_script(
			'msp-sorter-field',
			$field_url . "sorter$min.js",
			array( 'msp-jquery', 'mdc', 'msp-jquery-ui-sortable' ),
			md5( filectime( $field_path . "sorter$min.js" ) ),
			true
		);
	}

	function render() {
		$options       = $this->options;
		$lists         = array( 'default' );
		$items         = array();
		$label         = array( 'default' => false );
		$value         = $this->value;
		$default_value = $this->default_value;

		$is_multi_list = isset( $options[ 'lists' ] ) && is_array( $options[ 'lists' ] );
		if ( $is_multi_list ) {
			$lists = $options[ 'lists' ];
		}
		if ( isset( $options[ 'items' ] ) && is_array( $options[ 'items' ] ) ) {
			$items = $options[ 'items' ];
		}
		if ( isset( $options[ 'label' ] ) ) {
			if ( $is_multi_list ) {
				if ( is_string( $options[ 'label' ] ) ) {
					foreach ( $lists as $list ) {
						$label[ $list ] = $options[ 'label' ];
					}
				} else if ( is_array( $options[ 'label' ] ) ) {
					foreach ( $lists as $list ) {
						$label[ $list ] = isset( $options[ 'label' ][ $list ] ) ? $options[ 'label' ][ $list ] : false;
					}
				}
			} else {
				$label[ 'default' ] = is_string( $options[ 'label' ] ) ? $options[ 'label' ] : false;
			}
		}
		if ( ! $is_multi_list ) {
			$value = array( 'default' => $value );
		}
		foreach ( array_keys( $items ) as $item_name ) {
			$is_set = false;
			for ( $i = 0; $i < count( $value ) && ! $is_set; $i ++ ) {
				$is_set = in_array( $item_name, $value[ array_keys( $value )[ $i ] ] );
			}
			if ( ! $is_set ) {
				$value[ array_keys( $value )[ 0 ] ][] = $item_name;
			}
		}
		?>
        <div class="sorter-field-container"
             data-msp-field-id="<?php echo esc_attr( $this->id ); ?>"
             data-msp-field-section-id="<?php echo esc_attr( $this->section_id ); ?>"
             data-msp-field-default-value="<?php echo esc_attr( json_encode( $default_value ) ); ?>"
             data-msp-sorter-field-is-multi-list="<?php echo $is_multi_list ? 'true' : 'false'; ?>">
			<?php foreach ( $lists as $list ) { ?>
                <div class="mdc-elevation--z8 sorter-filed-list">
					<?php if ( is_string( $label[ $list ] ) ) { ?>
                        <h3><?php echo esc_html( $label[ $list ] ); ?></h3>
					<?php } ?>
                    <ul data-msp-sorter-field-list-id="<?php echo esc_attr( $list ); ?>">
						<?php foreach ( $value[ $list ] as $item ) { ?>
                            <li class="msp-field-sorter-item"
                                data-msp-sorter-field-list-item-id="<?php echo esc_attr( $item ); ?>"><?php
								echo esc_html( $items[ $item ] ); ?></li>
						<?php } ?>
                    </ul>
                </div>
			<?php } ?>
        </div>
		<?php
	}

	function check_value( $value ) {
		if ( ! is_array( $value ) ) {
			$value = array();
		}
		if ( isset( $this->options[ 'lists' ] ) && is_array( $this->options[ 'lists' ] ) ) {
			foreach ( $this->options[ 'lists' ] as $list ) {
				if ( ! isset( $value[ $list ] ) || ! is_array( $value[ $list ] ) ) {
					$value[ $list ] = array();
				}
			}
			foreach ( $value as $list => $value_list ) {
				if ( ! is_array( $value_list ) ) {
					unset( $value[ $list ] );
				}
			}
		}

		return $value;
	}
}