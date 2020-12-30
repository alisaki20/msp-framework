let option_name = $( '#msp' ).attr( 'data-msp-option-name' );
let version = parseInt( $( '#msp' ).attr( 'data-msp-version' ) );

$( '#msp #menu > ul > li[data-section-id]' ).each( function ( index, element ) {
    let id = $( element ).attr( 'data-section-id' );
    let name = $( element ).attr( 'data-section-name' );
    msp.sections[ id ] = new msp.Section( id, name );
} );

saveNotice = $( '#save-notice' ).get( 0 );
saveNotice = new mdc.snackbar.MDCSnackbar( saveNotice );
saveNotice.timeoutMs = -1;
new mdc.ripple.MDCRipple( $( '#save-notice #save-notice-hide-button' ).get( 0 ) );
new mdc.ripple.MDCRipple( $( '#save-notice #save-notice-save-button' ).get( 0 ) );
$( '#save-notice #save-notice-save-button' ).click( function () {
    $( msp.template.saveButtons ).click();
} );

saveMessage = $( '#save-message' ).get( 0 );
saveMessage = new mdc.snackbar.MDCSnackbar( saveMessage );

saveValidationMessages = $( '#save-validation-messages' ).get( 0 );
saveValidationMessages = new mdc.dialog.MDCDialog( saveValidationMessages );

msp.template = {};

msp.template.getSections = () => {
    const sections = [];
    $( '#msp #menu > ul > li[data-section-id]' ).each( function ( index, element ) {
        sections.push( $( element ).attr( 'data-section-id' ) )
    } );
    return sections;
};
msp.template.isThereSelectedSection = () => {
    return $( '#msp #menu > ul > li.active' ).length !== 0;
};
msp.template.getSectionsCount = () => {
    return $( '#msp #menu > ul > li[data-section-id]' ).length
};
msp.template.getSectionElements = ( section_id ) => {
    return {
        listElement: $( '#msp #menu > ul > li[data-section-id="' + section_id + '"]' ).get( 0 ),
        contentElement: $( '#msp #settings-options > section[data-section-id="' + section_id + '"]' ).get( 0 )
    };
};
msp.template.getSectionElementsArray = ( section_id ) => {
    const section_elements = msp.template.getSectionElements( section_id );
    return [ section_elements.listElement, section_elements.contentElement ]
};
msp.template.getCurrentSectionID = () => {
    if ( msp.template.isThereSelectedSection() ) {
        return $( '#msp #menu > ul > li.active' ).attr( 'data-section-id' );
    } else {
        return undefined;
    }
};
msp.template.getCurrentSectionElements = () => {
    let currentSectionID = msp.template.getCurrentSectionID();
    if ( currentSectionID !== undefined ) return msp.template.getSectionElements( currentSectionID );
    else return false;
};
msp.template.getCurrentSectionElementsArray = () => {
    let currentSectionID = msp.template.getCurrentSectionID();
    if ( currentSectionID !== undefined ) return msp.template.getSectionElementsArray( currentSectionID );
    else return false;
};
msp.template.setCurrentSection = ( section_id ) => {
    if ( msp.template.isThereSelectedSection() ) {
        $( msp.template.getCurrentSectionElementsArray() ).removeClass( 'active' );
    }
    $( msp.template.getSectionElementsArray( section_id ) ).addClass( 'active' );
};
msp.template.showSaveChangeMessage = () => {
    saveNotice.open()
};
msp.template.hideSaveChangeMessage = () => {
    saveNotice.close()
};

if ( msp.template.getSections().indexOf( getCookieValue( 'msp-last-section' ) ) >= 0 ) {
    msp.template.setCurrentSection( getCookieValue( 'msp-last-section' ) );
}

if ( !msp.template.isThereSelectedSection() && msp.template.getSectionsCount() !== 0 ) {
    msp.template.setCurrentSection( msp.template.getSections()[ 0 ] );
}

$( '#menu > ul > li' ).click( function () {
    if ( !$( this ).hasClass( 'active' ) ) {
        msp.template.setCurrentSection( $( this ).attr( 'data-section-id' ) );
        document.cookie = "msp-last-section=" + $( this ).attr( 'data-section-id' ) + "; path=/;secure";
    }
} );

sectionsViewToggle.listen( 'MDCIconButtonToggle:change', function ( data ) {
    $( '#msp' ).toggleClass( 'sections-all-view', data[ 'detail' ][ 'isOn' ] );
    document.cookie = "msp-all-sections-view=" + ( data[ 'detail' ][ 'isOn' ] ).toString() + "; path=/;secure";
} );

msp.template.saveButtons = $( '.msp-save-button' ).get();
msp.template.resetSectionButtons = $( '.msp-reset-section-button' ).get();
msp.template.resetAllButtons = $( '.msp-reset-all-button' ).get();

buttons = $.extend( {}, msp.template.saveButtons, msp.template.resetSectionButtons, msp.template.resetAllButtons );

$.each( buttons, function ( _, button ) {
    new mdc.ripple.MDCRipple( button );
} );

$( msp.template.saveButtons ).click( function () {
    if ( !$( msp.template.saveButtons ).prop( 'disabled' ) ) {
        $( msp.template.saveButtons ).prop( 'disabled', true );
        // noinspection JSUnresolvedVariable
        saveMessage.labelText = msp_strings[ 'saving' ];
        saveMessage.timeoutMs = -1;
        saveMessage.open();

        msp.saveFields( option_name, function ( message, validation_messages ) {
            saveMessage.close();
            saveMessage.labelText = message;
            if ( Object.keys( validation_messages ).length > 0 ) {
                let messages_list = $( '#save-validation-messages-content > ol' );
                messages_list.empty();
                $.each( validation_messages, function ( field_name, field_data ) {
                    messages_list.append( '<li><b>' + field_data[ 'title' ] + ' [ ' + field_data[ 'section' ] +
                        ' ]</b><br>' + field_data[ 'message' ] + '</li>' );
                } );
                saveValidationMessages.open();
            }
        }, function ( message ) {
            saveMessage.close();
            saveMessage.labelText = message;
        }, function () {
            saveMessage.timeoutMs = 5000;
            saveMessage.open();
            $( msp.template.saveButtons ).prop( 'disabled', false );
        } );
    }
} );

$( msp.template.resetSectionButtons ).click( function () {
    if ( !msp.template.isThereSelectedSection() ||
        !msp.sections.hasOwnProperty( msp.template.getCurrentSectionID() ) ) {
        return;
    }
    $.each( msp.sections[ msp.template.getCurrentSectionID() ].fields, function ( field_name, field ) {
        field.setValue( field.getDefaultValue() );
    } );
    msp.onFieldChange();
} );

$( msp.template.resetAllButtons ).click( function () {
    $.each( msp.fields, function ( field_name, field ) {
        field.setValue( field.getDefaultValue() );
    } );
    msp.onFieldChange();
} );

menuShowButton = $( '#menu-show-button' ).get( 0 );
menuHideButton = $( '#menu-hide-button' ).get( 0 );

( ( ripple1, ripple2 ) => {
    ripple1.unbounded = true;
    ripple2.unbounded = true;
} )( new mdc.ripple.MDCRipple( menuShowButton ), new mdc.ripple.MDCRipple( menuHideButton ) );

$( menuShowButton ).click( function () {
    $( '#msp #sidebar' ).addClass( 'show-menu' );
} );

$( menuHideButton ).click( function () {
    $( '#msp #sidebar' ).removeClass( 'show-menu' );
} );