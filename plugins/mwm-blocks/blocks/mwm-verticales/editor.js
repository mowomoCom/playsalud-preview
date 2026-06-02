( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, TextareaControl } = wp.components;
	const { InspectorControls, URLInputButton, useBlockProps } = wp.blockEditor;

	function renderVerticalesEdit( props ) {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'mwm-verticales-editor' } );

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Cabecera', 'mwm-blocks' ), initialOpen: true },
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
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Card PlayCare', 'mwm-blocks' ), initialOpen: true },
					el( TextControl, {
						label: __( 'Titulo', 'mwm-blocks' ),
						value: attributes.careTitle || '',
						onChange: function ( value ) {
							setAttributes( { careTitle: value } );
						},
					} ),
					el( TextareaControl, {
						label: __( 'Descripcion', 'mwm-blocks' ),
						value: attributes.careText || '',
						onChange: function ( value ) {
							setAttributes( { careText: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Texto boton', 'mwm-blocks' ),
						value: attributes.careButtonText || '',
						onChange: function ( value ) {
							setAttributes( { careButtonText: value } );
						},
					} ),
					el( URLInputButton, {
						label: __( 'URL boton', 'mwm-blocks' ),
						url: attributes.careButtonUrl || '',
						onChange: function ( value ) {
							setAttributes( { careButtonUrl: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Card PlayAcademy', 'mwm-blocks' ), initialOpen: false },
					el( TextControl, {
						label: __( 'Titulo', 'mwm-blocks' ),
						value: attributes.academyTitle || '',
						onChange: function ( value ) {
							setAttributes( { academyTitle: value } );
						},
					} ),
					el( TextareaControl, {
						label: __( 'Descripcion', 'mwm-blocks' ),
						value: attributes.academyText || '',
						onChange: function ( value ) {
							setAttributes( { academyText: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Texto boton', 'mwm-blocks' ),
						value: attributes.academyButtonText || '',
						onChange: function ( value ) {
							setAttributes( { academyButtonText: value } );
						},
					} ),
					el( URLInputButton, {
						label: __( 'URL boton', 'mwm-blocks' ),
						url: attributes.academyButtonUrl || '',
						onChange: function ( value ) {
							setAttributes( { academyButtonUrl: value } );
						},
					} )
				)
			),
			el(
				'section',
				blockProps,
				el(
					'div',
					{ className: 'mwm-container' },
					el( 'p', { className: 'mwm-eyebrow' }, attributes.eyebrow || '' ),
					el( 'h2', { className: 'mwm-verticales__title' }, attributes.title || '' ),
					el(
						'div',
						{ className: 'mwm-verticales__grid' },
						el(
							'article',
							{ className: 'mwm-verticales__card' },
							el( 'h3', null, attributes.careTitle || '' ),
							el( 'p', null, attributes.careText || '' ),
							el(
								'a',
								{ href: attributes.careButtonUrl || '#', className: 'mwm-btn mwm-btn--ghost mwm-btn--md' },
								attributes.careButtonText || ''
							)
						),
						el(
							'article',
							{ className: 'mwm-verticales__card' },
							el( 'h3', null, attributes.academyTitle || '' ),
							el( 'p', null, attributes.academyText || '' ),
							el(
								'a',
								{ href: attributes.academyButtonUrl || '#', className: 'mwm-btn mwm-btn--primary mwm-btn--md' },
								attributes.academyButtonText || ''
							)
						)
					)
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/verticales-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/verticales' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderVerticalesEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
