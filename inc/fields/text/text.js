$( '.text-field-inputs' ).each( function ( index, text_field ) {
    let id = $( text_field ).attr( 'data-msp-field-id' );
    let section_id = $( text_field ).attr( 'data-msp-field-section-id' );

    let is_multi_input = $( text_field ).attr( 'data-msp-text-field-is-multi-input' ) === 'true';

    let inputs = {};
    $.each( $( text_field ).children( 'label.mdc-text-field' ), function ( index, textFieldInput ) {
        inputs[ $( textFieldInput ).attr( 'data-msp-text-field-input-name' ) ] =
            new mdc.textField.MDCTextField( textFieldInput );
        $( textFieldInput ).find( '.mdc-select__icon' ).each( function ( _, element ) {
            new mdc.textField.MDCTextFieldIcon( element );
        } );
    } );

    let default_value = {};
    if ( is_multi_input ) {
        $.each( inputs, function ( input_name, input ) {
            default_value[ input_name ] = $( input.root ).attr( 'data-msp-text-field-input-default-value' );
        } );
    } else {
        default_value = $( inputs[ Object.keys( inputs )[ 0 ] ].root )
            .attr( 'data-msp-text-field-input-default-value' );
    }

    let field = $.extend( new msp.Field( id, section_id, text_field ), {
        getValue: () => {
            if ( is_multi_input ) {
                let value = {};
                $.each( inputs, function ( input_name, input ) {
                    value[ input_name ] = input.value;
                } );
                return value;
            } else {
                return inputs[ Object.keys( inputs )[ 0 ] ].value;
            }
        },
        setValue: ( new_value ) => {
            if ( typeof new_value == 'object' ) {
                if ( is_multi_input ) {
                    $.each( inputs, function ( input_name, input ) {
                        if ( new_value.hasOwnProperty( input_name ) && typeof new_value[ input_name ] == 'string' )
                            input.value = new_value[ input_name ];
                    } );
                    return true;
                }
            }
            if ( typeof new_value == 'string' ) {
                inputs[ Object.keys( inputs )[ 0 ] ].value = new_value;
                return true;
            }
            return false;
        },
        getDefaultValue: () => {
            return default_value;
        }
    } );

    field = msp.addField( field );

    $.each( inputs, function ( input_name, input ) {
        $( input.root ).find( '.mdc-text-field__input' ).change( function () {
            field.onChange();
        } );
    } );
} );