$( '.textarea-field-container' ).each( function ( index, textarea_field ) {
    let id = $( textarea_field ).attr( 'data-msp-field-id' );
    let section_id = $( textarea_field ).attr( 'data-msp-field-section-id' );
    let default_value = $( textarea_field ).attr( 'data-msp-textarea-field-default-value' );

    let mdcTextarea = new mdc.textField.MDCTextField( $( textarea_field ).children( '.mdc-text-field' ).get( 0 ) );

    let field = $.extend( new msp.Field( id, section_id, textarea_field ), {
        getValue: () => {
            return mdcTextarea.value;
        },
        setValue: ( new_value ) => {
            if ( typeof new_value == 'string' ) {
                mdcTextarea.value = new_value;
                return true;
            }
            return false;
        },
        getDefaultValue: () => {
            return default_value;
        }
    } );

    field = msp.addField( field );

    $( textarea_field ).find( '.mdc-text-field__input' ).change( function () {
        field.onChange();
    } );
} );