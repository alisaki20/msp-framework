$( '.switch-field-container' ).each( function ( index, switch_field ) {
    let id = $( switch_field ).attr( 'data-msp-field-id' );
    let section_id = $( switch_field ).attr( 'data-msp-field-section-id' );
    let default_value = $( switch_field ).attr( 'data-msp-field-default-value' ) === '1';

    let mdcSwitch = new mdc.switchControl.MDCSwitch( $( switch_field ).children( '.mdc-switch' ).get( 0 ) );

    let field = $.extend( new msp.Field( id, section_id, switch_field ), {
        getValue: () => {
            return mdcSwitch.checked;
        },
        setValue: ( new_value ) => {
            if ( typeof new_value == 'boolean' ) {
                mdcSwitch.checked = new_value;
                return true;
            }
            return false;
        },
        getDefaultValue: () => {
            return default_value;
        }
    } );

    field = msp.addField( field );

    $( switch_field ).find( '.mdc-switch__native-control' ).change( function () {
        field.onChange();
    } );
} );