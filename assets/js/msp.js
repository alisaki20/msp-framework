// noinspection JSUnresolvedVariable
const msp = {
    fields: {},
    sections: {},
    template: {
        getSections: () => {
        },
        isThereSelectedSection: () => {
        },
        getSectionsCount: () => {
        },
        getSectionElements: () => {
        },
        getSectionElementsArray: () => {
        },
        getCurrentSectionID: () => {
        },
        getCurrentSectionElements: () => {
        },
        getCurrentSectionElementsArray: () => {
        },
        setCurrentSection: () => {
        },
        showSaveChangeMessage: () => {
        },
        hideSaveChangeMessage: () => {
        },
    },
    isThereChanges: false,
};

msp.Field = function ( id, section, rootElement ) {
    this.id = id;
    this.section = section;
    this.rootElement = rootElement;
    this.getValue = () => {
    };
    this.setValue = () => {
    };
    this.getDefaultValue = () => {
    };
    this.initialize = () => {
    };
    this.onChange = () => {
    };
};
msp.Section = function ( id, name ) {
    this.id = id;
    this.name = name;
    this.fields = {};
};
msp.onFieldChange = () => {
    msp.isThereChanges = true;
    msp.template.showSaveChangeMessage();
};
msp.addField = ( field ) => {
    msp.fields[ field.id ] = field;
    if ( msp.sections.hasOwnProperty( field.section ) ) msp.sections[ field.section ].fields[ field.id ] = field;
    field.onChange = msp.onFieldChange;
    return field;
};
msp.saveFields = ( option_name, onSuccess, onError, onComplete ) => {
    msp.template.hideSaveChangeMessage();
    // noinspection JSUnresolvedVariable
    let url = msp_strings[ 'ajax_url' ];
    let action = 'msp-framework-' + option_name + '-set-values';
    let values = {};
    $.each( msp.fields, function ( name, field ) {
        values[ name ] = field.getValue();
    } );
    values = JSON.stringify( values );

    $.ajax( url, {
        type: 'POST',
        dataType: 'json',
        data: {
            action: action,
            values: values,
        },
    } ).done( function ( response ) {
        msp.isThereChanges = false;
        if ( response.success ) {
            let validation_messages = {};
            $.each( response.data.values, function ( field_name, field_data ) {
                if ( msp.fields.hasOwnProperty( field_name ) ) {
                    msp.fields[ field_name ].setValue( field_data[ 'value' ] );
                    if ( field_data.hasOwnProperty( 'message' ) )
                        validation_messages[ field_name ] = field_data;
                }
            } );
            onSuccess( response.data.message, validation_messages );
        } else onError( response.data.message )
    } ).fail( function ( jqXHR, textStatus ) {
        if ( textStatus === 'timeout' ) {
            // noinspection JSUnresolvedVariable
            onError( msp_strings[ 'connection-timeout' ] );
        } else {
            // noinspection JSUnresolvedVariable
            onError( msp_strings[ 'unexpected-error' ] );
        }
    } ).always( function () {
        onComplete();
    } );
};
msp.checkStringBackup = ( option_name, string_json ) => {
    try {
        let json = JSON.parse( string_json );
        if ( json.hasOwnProperty( 'option-name' ) && typeof json[ 'option-name' ] === 'string' &&
            json.hasOwnProperty( 'version' ) && typeof json[ 'version' ] === 'number' &&
            json.hasOwnProperty( 'sections' ) && typeof json[ 'sections' ] === 'object' &&
            Object.keys( json[ 'sections' ] ).length > 0 ) {
            let filteredSections = {};
            $.each( json[ 'sections' ], function ( section_id, fields ) {
                if ( msp.sections.hasOwnProperty( section_id ) && typeof fields === 'object' ) {
                    filteredSections[ section_id ] = fields;
                }
            } );
            if ( Object.keys( filteredSections ).length > 0 ) {
                if ( json[ 'option-name' ] === option_name ) {
                    json[ 'sections' ] = filteredSections;
                    return { success: true, data: json }
                } else {
                    // noinspection JSUnresolvedVariable
                    return { success: false, message: msp_strings[ 'other-setting-backup' ] }
                }
            }
        }
    } catch {
    }
    // noinspection JSUnresolvedVariable
    return { success: false, message: msp_strings[ 'backup-not-valid' ] }
};
msp.restore = backup => {
    $.each( backup[ 'sections' ], function ( section_id, backup_fields ) {
        $.each( msp.sections[ section_id ].fields, function ( field_id, field ) {
            if ( backup_fields.hasOwnProperty( field_id ) ) {
                field.setValue( backup_fields[ field_id ] );
            }
        } );
    } );
    // noinspection JSUnresolvedVariable
    return msp_strings[ 'success-restore' ];
};
msp.createBackup = ( option_name, version, requiredSections ) => {
    let backup = {
        'option-name': option_name,
        'version': version,
        'sections': {}
    };
    $.each( requiredSections, function ( _, section_name ) {
        let fields_values = {};
        $.each( msp.sections[ section_name ].fields, function ( field_name, field ) {
            fields_values[ field_name ] = field.getValue();
        } );
        backup.sections[ section_name ] = fields_values;
    } );
    return backup;
};
msp.createBackupAsFile = ( option_name, version, requiredSections ) => {
    let backup = msp.createBackup( option_name, version, requiredSections );
    let fileContent = JSON.stringify( backup );
    download( fileContent, ( option_name ? option_name + '-' : '' ) + 'settings-backup.json', 'application/json' );
};

$( window ).on( 'beforeunload', function () {
    if ( msp.isThereChanges ) {
        // noinspection JSUnresolvedVariable
        return msp_strings[ 'save-alert' ];
    }
} );

function getCookieValue( a ) {
    let b = document.cookie.match( '(^|[^;]+)\\s*' + a + '\\s*=\\s*([^;]+)' );
    return b ? b.pop() : '';
}