$( '.select-field-container' ).each( function ( index, select_field ) {
    let id = $( select_field ).attr( 'data-msp-field-id' );
    let section_id = $( select_field ).attr( 'data-msp-field-section-id' );
    let default_value = $( select_field ).attr( 'data-msp-select-field-default-value' );

    let mdcSelect = new mdc.select.MDCSelect( $( select_field ).children( '.mdc-select' ).get( 0 ) );
    if ( $( select_field ).find( '.mdc-select__icon' ).length > 0 )
        new mdc.select.MDCSelectIcon( $( select_field ).find( '.mdc-select__icon' ).get( 0 ) );

    let ignore_next_change = false;

    let field = $.extend( new msp.Field( id, section_id, select_field ), {
        getValue: () => {
            return mdcSelect.value;
        },
        setValue: ( new_value ) => {
            if ( typeof new_value == 'string' ) {
                ignore_next_change = true;
                mdcSelect.value = new_value;
                return true;
            }
            return false;
        },
        getDefaultValue: () => {
            return default_value;
        }
    } );

    field = msp.addField( field );

    mdcSelect.listen( 'MDCSelect:change', function () {
        if ( ignore_next_change ) {
            ignore_next_change = false;
            return;
        }
        field.onChange();
    } );
} );