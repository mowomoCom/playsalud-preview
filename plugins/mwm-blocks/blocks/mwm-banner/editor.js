( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, TextareaControl } = wp.components;
	const { InspectorControls, useBlockProps } = wp.blockEditor;

	function renderBannerEdit( props ) {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'mwm-banner-editor' } );

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Contenido', 'mwm-blocks' ), initialOpen: true },
					el( TextControl, {
						label: __( 'Eyebrow', 'mwm-blocks' ),
						value: attributes.eyebrow || '',
						onChange: function ( value ) {
							setAttributes( { eyebrow: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Titulo', 'mwm-blocks' ),
						value: attributes.title || '',
						onChange: function ( value ) {
							setAttributes( { title: value } );
						},
					} ),
					el( TextareaControl, {
						label: __( 'Descripcion', 'mwm-blocks' ),
						value: attributes.lead || '',
						onChange: function ( value ) {
							setAttributes( { lead: value } );
						},
					} )
				)
			),
			el(
				'section',
				blockProps,
				el(
					'div',
					{ className: 'mwm-container mwm-banner__inner' },
					el( 'p', { className: 'mwm-eyebrow' }, attributes.eyebrow || '' ),
					el( 'h2', null, attributes.title || '' ),
					el( 'p', null, attributes.lead || '' )
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/banner-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/banner' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderBannerEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
