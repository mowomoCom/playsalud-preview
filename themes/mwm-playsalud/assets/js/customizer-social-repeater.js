( function( $, wp ) {
	'use strict';

	function escapeHtml( value ) {
		return String( value || '' )
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' )
			.replace( /'/g, '&#039;' );
	}

	function getRowMarkup( item ) {
		var imageId = parseInt( item.image_id || 0, 10 );
		var text = item.text || '';
		var hasImage = !!imageId;
		var previewHtml = item.preview_url ? '<img class="mwm-social-repeater__preview-image" src="' + escapeHtml( item.preview_url ) + '" alt="" />' : '';

		return (
			'<div class="mwm-social-repeater__item">' +
				'<div class="mwm-social-repeater__preview' + ( hasImage ? ' has-image' : '' ) + '">' + previewHtml + '</div>' +
				'<input type="hidden" class="mwm-social-repeater__image-id" value="' + imageId + '" />' +
				'<div class="mwm-social-repeater__buttons">' +
					'<button type="button" class="button mwm-social-repeater__select-image">Seleccionar imagen</button>' +
					'<button type="button" class="button-link mwm-social-repeater__remove-image">Quitar imagen</button>' +
				'</div>' +
				'<input type="text" class="widefat mwm-social-repeater__text" value="' + escapeHtml( text ) + '" placeholder="Texto de la red social" />' +
				'<button type="button" class="button-link-delete mwm-social-repeater__remove-row">Eliminar</button>' +
			'</div>'
		);
	}

	function updateControlValue( $control ) {
		var items = [];

		$control.find( '.mwm-social-repeater__item' ).each( function() {
			var $item = $( this );
			var imageId = parseInt( $item.find( '.mwm-social-repeater__image-id' ).val() || 0, 10 );
			var text = String( $item.find( '.mwm-social-repeater__text' ).val() || '' ).trim();

			if ( imageId || text ) {
				items.push(
					{
						image_id: imageId,
						text: text
					}
				);
			}
		} );

		$control.find( '.mwm-social-repeater__value' ).val( JSON.stringify( items ) ).trigger( 'change' );
	}

	function setRowImage( $row, imageId, imageUrl ) {
		var $preview = $row.find( '.mwm-social-repeater__preview' );

		$row.find( '.mwm-social-repeater__image-id' ).val( imageId );

		if ( imageUrl ) {
			$preview.addClass( 'has-image' );
			$preview.html( '<img class="mwm-social-repeater__preview-image" src="' + escapeHtml( imageUrl ) + '" alt="" />' );
			return;
		}

		$preview.removeClass( 'has-image' );
		$preview.empty();
	}

	function initControl( control ) {
		var $control = $( control.container );
		var mediaFrame = null;

		if ( $control.data( 'mwmSocialInit' ) ) {
			return;
		}

		$control.data( 'mwmSocialInit', true );

		$control.on( 'click', '.mwm-social-repeater__add-row', function( event ) {
			event.preventDefault();
			$control.find( '.mwm-social-repeater__items' ).append( getRowMarkup( {} ) );
			updateControlValue( $control );
		} );

		$control.on( 'click', '.mwm-social-repeater__remove-row', function( event ) {
			event.preventDefault();
			$( this ).closest( '.mwm-social-repeater__item' ).remove();
			updateControlValue( $control );
		} );

		$control.on( 'click', '.mwm-social-repeater__remove-image', function( event ) {
			event.preventDefault();
			var $row = $( this ).closest( '.mwm-social-repeater__item' );
			setRowImage( $row, 0, '' );
			updateControlValue( $control );
		} );

		$control.on( 'input change', '.mwm-social-repeater__text', function() {
			updateControlValue( $control );
		} );

		$control.on( 'click', '.mwm-social-repeater__select-image', function( event ) {
			var $row = $( this ).closest( '.mwm-social-repeater__item' );
			event.preventDefault();

			mediaFrame = wp.media(
				{
					title: 'Seleccionar imagen',
					button: {
						text: 'Usar imagen'
					},
					multiple: false
				}
			);

			mediaFrame.on( 'select', function() {
				var attachment = mediaFrame.state().get( 'selection' ).first().toJSON();
				var imageUrl = attachment.url;

				if ( attachment.sizes && attachment.sizes.thumbnail ) {
					imageUrl = attachment.sizes.thumbnail.url;
				}

				setRowImage( $row, attachment.id, imageUrl );
				updateControlValue( $control );
			} );

			mediaFrame.open();
		} );
	}

	wp.customize.bind( 'ready', function() {
		wp.customize.control.each( function( control ) {
			if ( 'mwm_social_repeater' === control.params.type ) {
				initControl( control );
			}
		} );
	} );
} )( jQuery, wp );
