( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { Button, PanelBody, TextControl, TextareaControl } = wp.components;
	const {
		InspectorControls,
		MediaUpload,
		MediaUploadCheck,
		useBlockProps,
	} = wp.blockEditor;

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
				),
				el(
					PanelBody,
					{ title: __( 'Imagen', 'mwm-blocks' ), initialOpen: false },
					el( TextControl, {
						label: __( 'Texto alternativo', 'mwm-blocks' ),
						value: attributes.imageAlt || '',
						onChange: function ( value ) {
							setAttributes( { imageAlt: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Caption', 'mwm-blocks' ),
						value: attributes.caption || '',
						onChange: function ( value ) {
							setAttributes( { caption: value } );
						},
					} ),
					el(
						MediaUploadCheck,
						null,
						el( MediaUpload, {
							onSelect: function ( media ) {
								setAttributes( {
									imageUrl: media && media.url ? media.url : '',
									imageAlt: media && media.alt ? media.alt : attributes.imageAlt || '',
								} );
							},
							allowedTypes: [ 'image' ],
							render: function ( mediaProps ) {
								return el(
									Button,
									{
										onClick: mediaProps.open,
										variant: 'secondary',
									},
									attributes.imageUrl
										? __( 'Reemplazar imagen', 'mwm-blocks' )
										: __( 'Seleccionar imagen', 'mwm-blocks' )
								);
							},
						} )
					)
				),
				attributes.imageUrl
					? el(
							Button,
							{
								variant: 'link',
								isDestructive: true,
								onClick: function () {
									setAttributes( { imageUrl: '' } );
								},
							},
							__( 'Quitar imagen', 'mwm-blocks' )
					  )
					: null
			),
			el(
				'section',
				blockProps,
				el(
					'div',
					{ className: 'mwm-banner__strip' },
					el(
						'figure',
						{ className: 'mwm-banner__figure' },
						attributes.imageUrl
							? el( 'img', {
									src: attributes.imageUrl,
									alt: attributes.imageAlt || '',
							  } )
							: el(
									'div',
									{ className: 'mwm-banner__placeholder' },
									__( 'Selecciona una imagen desde el panel lateral.', 'mwm-blocks' )
							  ),
						attributes.caption
							? el( 'figcaption', { className: 'mwm-banner__caption' }, attributes.caption )
							: null
					),
					el(
						'div',
						{ className: 'mwm-container mwm-banner__inner' },
						el(
							'p',
							{ className: 'mwm-eyebrow' },
							el( 'span', { className: 'mwm-banner__eyebrow-dot' } ),
							attributes.eyebrow || ''
						),
						el( 'h2', { className: 'mwm-banner__title' }, attributes.title || '' ),
						el( 'p', { className: 'mwm-banner__lead' }, attributes.lead || '' )
					)
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
