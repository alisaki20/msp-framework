// Variables

let backupToRestore = {};

restoreFromFileInput = $( '#msp-restore-from-file-input' );

new mdc.ripple.MDCRipple( restoreFromFileButton = $( '#msp-restore-from-file-button' ).get( 0 ) );
new mdc.ripple.MDCRipple( restoreFromClipboardButton = $( '#msp-restore-from-clipboard-button' ).get( 0 ) );
new mdc.ripple.MDCRipple(
    restoreFromClipboardRestoreButton = $( '#msp-restore-from-clipboard-restore-button' ).get( 0 )
);
new mdc.ripple.MDCRipple( backupToFileButton = $( '#msp-backup-to-file-button' ).get( 0 ) );
new mdc.ripple.MDCRipple( backupToClipboardButton = $( '#msp-backup-to-clipboard-button' ).get( 0 ) );
new mdc.ripple.MDCRipple( backupToClipboardCopyButton = $( '#msp-backup-to-clipboard-copy-button' ).get( 0 ) );

restoreFromClipboardJsonTectarea = new mdc.textField.MDCTextField(
    $( '#msp-restore-from-clipboard-json-textarea' ).get( 0 )
);
backupToClipboardJsonTectarea = new mdc.textField.MDCTextField(
    $( '#msp-backup-to-clipboard-json-textarea' ).get( 0 )
);

restoreFromClipboardBox = $( '#msp-restore-from-clipboard-box' ).get( 0 );
backupToClipboardBox = $( '#msp-backup-to-clipboard-box' ).get( 0 );

// Restore

restoreMessage = new mdc.snackbar.MDCSnackbar( $( '#restore-message' ).get( 0 ) );

restoreDialog = new mdc.dialog.MDCDialog( $( '#restore-dialog' ).get( 0 ) );
restoreDialogSectionList = new mdc.dataTable.MDCDataTable( $( '#restore-dialog-sections-list' ).get( 0 ) );

restoreDialog.listen( 'MDCDialog:opened', () => {
    $( restoreDialogSectionList.content ).empty();
    $.each( backupToRestore[ 'sections' ], function ( section_id, fields ) {
        let id = ( 'backup-dialog-section-' + section_id ).trim();
        let section_name = msp.sections[ section_id ][ 'name' ];
        let element = $(
            '<tr class="mdc-data-table__row mdc-data-table__row--selected"' +
            '    aria-selected="true">' +
            '    <td class="mdc-data-table__cell mdc-data-table__cell--checkbox">' +
            '        <div class="mdc-checkbox mdc-data-table__row-checkbox' +
            ' mdc-checkbox--selected">' +
            '            <input type="checkbox" class="mdc-checkbox__native-control"' +
            '                   checked="checked"/>' +
            '            <div class="mdc-checkbox__background">' +
            '                <svg xmlns="http://www.w3.org/2000/svg"' +
            '                     class="mdc-checkbox__checkmark" viewBox="0 0 24 24">' +
            '                    <path class="mdc-checkbox__checkmark-path" fill="none"' +
            '                          d="M1.73,12.91 8.1,19.28 22.79,4.59"/>' +
            '                </svg>' +
            '                <div class="mdc-checkbox__mixedmark"></div>' +
            '            </div>' +
            '            <div class="mdc-checkbox__ripple"></div>' +
            '        </div>' +
            '    </td>' +
            '    <th class="mdc-data-table__cell" scope="row"></th>' +
            '    <td class="mdc-data-table__cell mdc-data-table__cell--numeric"></td>' +
            '</tr>'
        );
        element.attr( 'data-row-id', section_id );
        element.find( 'input.mdc-checkbox__native-control' ).prop( 'id', id );
        element.find( 'input.mdc-checkbox__native-control' ).prop( 'for', id + '-label' );
        element.find( 'td.mdc-data-table__cell' ).eq( 1 ).prop( 'id', id + '-label' );
        element.find( 'td.mdc-data-table__cell' ).eq( 1 ).prop( 'for', id );
        element.find( 'td.mdc-data-table__cell' ).eq( 1 ).text( section_name );
        element.find( 'td.mdc-data-table__cell' ).eq( 2 ).text( Object.keys( fields ).length.toString() );
        $( restoreDialogSectionList.content ).append( element );
    } );
    restoreDialogSectionList.layout();
} );

restoreDialogSectionListOnChange = () => {
    if ( restoreDialogSectionList.getSelectedRowIds().length > 0 ) {
        $( restoreDialog.root ).find( 'button[data-mdc-dialog-action="restore"]' )
            .prop( 'disabled', false )
    } else {
        $( restoreDialog.root ).find( 'button[data-mdc-dialog-action="restore"]' )
            .prop( 'disabled', true )
    }
};
restoreDialogSectionList.listen( 'MDCDataTable:rowSelectionChanged', restoreDialogSectionListOnChange );
restoreDialogSectionList.listen( 'MDCDataTable:selectedAll', restoreDialogSectionListOnChange );
restoreDialogSectionList.listen( 'MDCDataTable:unselectedAll', restoreDialogSectionListOnChange );

restoreDialog.listen( 'MDCDialog:closed', ( action ) => {
    if ( action.detail.action === 'restore' ) {
        let requiredSections = {};
        $.each( backupToRestore[ 'sections' ], function ( section_id, fields ) {
            if ( restoreDialogSectionList.getSelectedRowIds().indexOf( section_id ) !== -1 ) {
                requiredSections[ section_id ] = fields
            }
        } );
        backupToRestore[ 'sections' ] = requiredSections;
        restoreMessage.labelText = msp.restore( backupToRestore );
        restoreMessage.open();
    } else {
        backupToRestore = {};
    }
} );

restorVersionWorningDialog = new mdc.dialog.MDCDialog( $( '#restore-version-warning-dialog' ).get( 0 ) );
restorVersionWorningDialog.focusTrap_.getFocusableElements = function () {
    return [ restorVersionWorningDialog.getInitialFocusEl_() ];
};

restorVersionWorningDialog.listen( 'MDCDialog:closed', ( action ) => {
    if ( action.detail.action === 'restore' ) {
        restoreDialog.open();
    } else {
        backupToRestore = {};
    }
} );

restoreFromString = string => {
    let result = msp.checkStringBackup( option_name, string );
    if ( result.success ) {
        if ( result.data.version === version ) {
            backupToRestore = result.data;
            restoreDialog.open();
        } else {
            backupToRestore = result.data;
            restorVersionWorningDialog.open();
        }
    } else {
        restoreMessage.labelText = result.message;
        restoreMessage.open();
    }
};

restoreFromFileInput.change( function () {
    let files = $( this ).prop( 'files' );
    if ( files !== undefined && files.length > 0 ) {
        let file = files[ 0 ], reader;

        reader = new FileReader();
        reader.readAsText( file, 'UTF-8' );

        reader.onload = readerEvent => {
            restoreFromString( readerEvent.target.result.toString() );
        };
    }
} );

$( restoreFromFileButton ).click( function () {
    restoreFromFileInput.click();
} );

$( restoreFromClipboardButton ).click( function () {
    if ( $( restoreFromClipboardBox ).hasClass( 'msp-clipboard-box-hidden' ) ) {
        $( restoreFromClipboardBox ).removeClass( 'msp-clipboard-box-hidden' );
    } else {
        $( restoreFromClipboardBox ).addClass( 'msp-clipboard-box-hidden' );
    }
} );

$( restoreFromClipboardRestoreButton ).click( function () {
    restoreFromString( restoreFromClipboardJsonTectarea.value );
} );

// Backup

backupDialog = new mdc.dialog.MDCDialog( $( '#backup-dialog' ).get( 0 ) );
backupDialogSectionList = new mdc.dataTable.MDCDataTable( $( '#backup-dialog-sections-list' ).get( 0 ) );

backupDialog.listen( 'MDCDialog:opened', () => {
    backupDialogSectionList.layout();
} );

backupDialogSectionListOnChange = () => {
    if ( backupDialogSectionList.getSelectedRowIds().length > 0 ) {
        $( backupDialog.root ).find( 'button[data-mdc-dialog-action="backup"]' )
            .prop( 'disabled', false )
    } else {
        $( backupDialog.root ).find( 'button[data-mdc-dialog-action="backup"]' )
            .prop( 'disabled', true )
    }
};
backupDialogSectionList.listen( 'MDCDataTable:rowSelectionChanged', backupDialogSectionListOnChange );
backupDialogSectionList.listen( 'MDCDataTable:selectedAll', backupDialogSectionListOnChange );
backupDialogSectionList.listen( 'MDCDataTable:unselectedAll', backupDialogSectionListOnChange );

let backupDialogDoneFunction = () => {
};
let backupDialogCloseFunction = () => {
};
showSectionsSelectDialog = function ( onDone, onClose ) {
    backupDialogDoneFunction = onDone;
    backupDialogCloseFunction = onClose;
    backupDialog.open();
};
backupDialog.listen( 'MDCDialog:closed', ( action ) => {
    if ( action.detail.action === 'backup' && backupDialogSectionList.getSelectedRowIds().length > 0 ) {
        backupDialogDoneFunction(
            backupDialogSectionList.getSelectedRowIds()
        );
    } else {
        backupDialogCloseFunction();
    }
} );

$( backupToFileButton ).click( function () {
    showSectionsSelectDialog( ( requiredSections ) => {
        msp.createBackupAsFile( option_name, version, requiredSections );
    }, () => {} )
} );

$( backupToClipboardButton ).click( function () {
    if ( $( backupToClipboardBox ).hasClass( 'msp-clipboard-box-hidden' ) ) {
        showSectionsSelectDialog( ( requiredSections ) => {
            backupToClipboardJsonTectarea.value = JSON.stringify(
                msp.createBackup( option_name, version, requiredSections )
            );
            $( backupToClipboardBox ).removeClass( 'msp-clipboard-box-hidden' );
        }, () => {} );
    } else {
        $( backupToClipboardBox ).addClass( 'msp-clipboard-box-hidden' );
    }
} );

$( backupToClipboardCopyButton ).click( function () {
    input = backupToClipboardJsonTectarea.input_;
    input.select();
    input.setSelectionRange( 0, $( input ).val().length ); /*For mobile devices*/
    document.execCommand( "copy" );
} );