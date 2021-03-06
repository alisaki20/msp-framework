$( '.sorter-field-container' ).each( function ( index, sorter_field ) {
    sorter_field = $( sorter_field );

    let id = $( sorter_field ).attr( 'data-msp-field-id' );
    let section_id = $( sorter_field ).attr( 'data-msp-field-section-id' );
    let default_value = JSON.parse( $( sorter_field ).attr( 'data-msp-field-default-value' ) );

    let is_multi_list = $( sorter_field ).attr( 'data-msp-sorter-field-is-multi-list' ) === 'true';

    let sortable_lists = {};
    let sortable_items = {};

    $.each( $( sorter_field ).find( '.sorter-filed-list ul' ), function ( _, sortable_list ) {
        $( sortable_list ).sortable( {
            connectWith: $( sorter_field ).find( '.sorter-filed-list ul' ),
            placeholder: 'placeholder',
            cursor: 'move',
            tolerance: 'pointer',
            items: '> li.msp-field-sorter-item',
            forcePlaceholderSize: true
        } );
        sortable_lists[ $( sortable_list ).attr( 'data-msp-sorter-field-list-id' ) ] = sortable_list;
        $.each( $( sortable_list ).children( 'li.msp-field-sorter-item' ), function ( _, item ) {
            sortable_items[ $( item ).attr( 'data-msp-sorter-field-list-item-id' ) ] = item;
        } );
    } );

    let field = $.extend( new msp.Field( id, section_id, sorter_field ), {
        getValue: () => {
            let value = {};
            $.each( sortable_lists, function ( sortable_list_id, sortable_list ) {
                value[ $( sortable_list ).attr( 'data-msp-sorter-field-list-id' ) ] = $( sortable_list ).sortable(
                    'toArray', { attribute: 'data-msp-sorter-field-list-item-id' }
                );
            } );
            if ( !is_multi_list ) value = value[ 'default' ];
            return value;
        },
        setValue: ( new_value ) => {
            if ( typeof new_value == 'object' ) {
                let sortable_lists_values = {};
                let sorted_items = [];

                if ( !is_multi_list ) new_value = { 'default': new_value };

                $.each( sortable_lists, function ( sortable_list_id ) {
                    let sortable_list_values = [];
                    if ( new_value.hasOwnProperty( sortable_list_id ) && typeof new_value[ sortable_list_id ] == 'object' ) {
                        $.each( new_value[ sortable_list_id ], function ( _, item_id ) {
                            if ( sortable_items.hasOwnProperty( item_id ) ) {
                                sortable_list_values.push( sortable_items[ item_id ] );
                                sorted_items.push( sortable_items[ item_id ] );
                            }
                        } );
                    }
                    sortable_lists_values[ sortable_list_id ] = sortable_list_values;
                } );
                $.each( sortable_items, function ( sortable_item_id, sortable_item ) {
                    if ( !sorted_items.includes( sortable_item ) )
                        sortable_lists_values[ Object.keys( sortable_lists_values )[ 0 ] ].push( sortable_item );
                } );
                $.each( sortable_lists_values, function ( sortable_list_id, sortable_list_values ) {
                    $.each( sortable_list_values, function ( _, item ) {
                        sortable_lists[ sortable_list_id ].append( item );
                    } );
                    $( sortable_lists[ sortable_list_id ] ).sortable( 'refresh' );
                } );
                return true;
            }
            return false;
        },
        getDefaultValue: () => {
            return default_value;
        },
    } );

    msp.addField( field );

    $( Object.values( sortable_lists ) ).on( "sortupdate", function () {
        field.onChange();
    } );
} );