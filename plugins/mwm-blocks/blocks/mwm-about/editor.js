( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, TextareaControl } = wp.components;
	const { InspectorControls, useBlockProps } = wp.blockEditor;

	function renderAboutEdit( props ) {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'mwm-about-editor' } );

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
					} ),
					el( TextareaControl, {
						label: __( 'Cita', 'mwm-blocks' ),
						value: attributes.quote || '',
						onChange: function ( value ) {
							setAttributes( { quote: value } );
						},
					} )
				)
			),
			el(
				'section',
				blockProps,
				el(
					'div',
					{ className: 'mwm-container mwm-about__grid' },
					el(
						'div',
						null,
						el( 'p', { className: 'mwm-eyebrow' }, attributes.eyebrow || '' ),
						el( 'h2', null, attributes.title || '' ),
						el( 'p', null, attributes.lead || '' )
					),
					el(
						'aside',
						{ className: 'mwm-about__quote' },
						el( 'p', null, attributes.quote || '' )
					)
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/about-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/about' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderAboutEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
