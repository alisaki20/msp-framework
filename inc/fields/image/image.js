$( '.image-field-container' ).each( function ( index, image_field ) {
    image_field = $( image_field );
    let frame,
        select_button = image_field.find( '.image-field-button-select' ),
        default_button = image_field.find( '.image-field-button-default' ),
        delete_button = image_field.find( '.image-field-button-delete' ),
        image_container = image_field.find( '.image-field-content-image' ),
        no_image_container = image_field.find( '.image-field-content-no-image' ),
        image_input = image_field.find( '.image-field-input' ),
        default_image_input = image_field.find( '.image-field-input-default' ),
        select_image = ( image ) => {
            image_container.append( '<img src="' + image.url + '" alt title />' );
            no_image_container.addClass( 'image-field-hidden' );
            image_container.removeClass( 'image-field-hidden' );
            image_input.val( JSON.stringify( image ) );
            select_button.addClass( 'image-field-hidden' );
            default_button.addClass( 'image-field-hidden' );
            delete_button.removeClass( 'image-field-hidden' );
        };

    let id = $( image_field ).attr( 'data-msp-field-id' );
    let section_id = $( image_field ).attr( 'data-msp-field-section-id' );
    let default_value = JSON.parse( default_image_input.val() );

    let field = $.extend( new msp.Field( id, section_id, image_field ), {
        getValue: () => {
            return JSON.parse( image_input.val() );
        },
        setValue: ( new_value ) => {
            if ( typeof new_value == 'object' ) {
                if ( new_value.hasOwnProperty( 'url' ) ) {
                    image_container.children( 'img' ).remove();
                    select_image( new_value );
                } else {
                    image_container.children( 'img' ).remove();
                    select_button.removeClass( 'image-field-hidden' );
                    default_button.removeClass( 'image-field-hidden' );
                    delete_button.addClass( 'image-field-hidden' );
                    image_container.addClass( 'image-field-hidden' );
                    no_image_container.removeClass( 'image-field-hidden' );
                    image_input.val( JSON.stringify( new_value ) );
                }
                return true;
            }
            return false;
        },
        getDefaultValue: () => {
            return default_value;
        }
    } );

    field = msp.addField( field );

    select_button.on( 'click', function ( event ) {
        event.preventDefault();

        if ( frame ) {
            frame.open();
            return;
        }

        // noinspection JSUnresolvedVariable
        frame = wp.media( {
            multiple: false,
            library: {
                type: 'image',
            }
        } );


        frame.on( 'select', function () {
            const attachment = frame.state().get( 'selection' ).first().toJSON();

            select_image( {
                'id': attachment.id,
                'width': attachment.width,
                'height': attachment.height,
                'url': attachment.url,
                'sizes': attachment.sizes,
                'title': attachment.title,
                'caption': attachment.caption,
                'alt': attachment.alt,
                'description': attachment.description,
            } );

            field.onChange();
        } );

        frame.open();
    } );

    default_button.on( 'click', function ( event ) {
        event.preventDefault();
        select_image( default_value );
        field.onChange();
    } );

    delete_button.on( 'click', function ( event ) {
        event.preventDefault();
        image_container.children( 'img' ).remove();
        select_button.removeClass( 'image-field-hidden' );
        default_button.removeClass( 'image-field-hidden' );
        delete_button.addClass( 'image-field-hidden' );
        image_container.addClass( 'image-field-hidden' );
        no_image_container.removeClass( 'image-field-hidden' );
        image_input.val( '{}' );
        field.onChange();
    } );
} );