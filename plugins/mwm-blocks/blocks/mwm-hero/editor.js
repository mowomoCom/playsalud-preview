( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, Button, TextControl, TextareaControl } = wp.components;
	const {
		InspectorControls,
		MediaUpload,
		MediaUploadCheck,
		RichText,
		URLInputButton,
		useBlockProps,
	} = wp.blockEditor;

	function renderHeroEdit( props ) {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'mwm-hero-editor' } );

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{
						title: __( 'Contenido principal', 'mwm-blocks' ),
						initialOpen: true,
					},
					el( TextControl, {
						label: __( 'Eyebrow', 'mwm-blocks' ),
						value: attributes.eyebrow || '',
						onChange: function ( value ) {
							setAttributes( { eyebrow: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Titulo principal', 'mwm-blocks' ),
						value: attributes.title || '',
						onChange: function ( value ) {
							setAttributes( { title: value } );
						},
					} ),
					el( TextareaControl, {
						label: __( 'Descripcion principal', 'mwm-blocks' ),
						value: attributes.lead || '',
						onChange: function ( value ) {
							setAttributes( { lead: value } );
						},
					} )
				),
				el(
					PanelBody,
					{
						title: __( 'Botones', 'mwm-blocks' ),
						initialOpen: true,
					},
					el( TextControl, {
						label: __( 'Texto boton primario', 'mwm-blocks' ),
						value: attributes.primaryText || '',
						onChange: function ( value ) {
							setAttributes( { primaryText: value } );
						},
					} ),
					el( URLInputButton, {
						label: __( 'URL boton primario', 'mwm-blocks' ),
						url: attributes.primaryUrl || '',
						onChange: function ( value ) {
							setAttributes( { primaryUrl: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Texto boton secundario', 'mwm-blocks' ),
						value: attributes.secondaryText || '',
						onChange: function ( value ) {
							setAttributes( { secondaryText: value } );
						},
					} ),
					el( URLInputButton, {
						label: __( 'URL boton secundario', 'mwm-blocks' ),
						url: attributes.secondaryUrl || '',
						onChange: function ( value ) {
							setAttributes( { secondaryUrl: value } );
						},
					} )
				),
				el(
					PanelBody,
					{
						title: __( 'Imagen', 'mwm-blocks' ),
						initialOpen: true,
					},
					el( TextControl, {
						label: __( 'Texto alternativo', 'mwm-blocks' ),
						value: attributes.imageAlt || '',
						onChange: function ( value ) {
							setAttributes( { imageAlt: value } );
						},
					} ),
					el( MediaUploadCheck, null, el( MediaUpload, {
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
					} ) ),
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
				)
			),
			el(
				'section',
				blockProps,
				el(
					'div',
					{ className: 'mwm-container mwm-hero__grid' },
					el(
						'div',
						null,
						el( RichText, {
							tagName: 'p',
							className: 'mwm-eyebrow',
							value: attributes.eyebrow || '',
							placeholder: __( 'Eyebrow', 'mwm-blocks' ),
							onChange: function ( value ) {
								setAttributes( { eyebrow: value } );
							},
						} ),
						el( RichText, {
							tagName: 'h1',
							className: 'mwm-hero__title',
							value: attributes.title || '',
							placeholder: __( 'Titulo principal', 'mwm-blocks' ),
							onChange: function ( value ) {
								setAttributes( { title: value } );
							},
						} ),
						el( RichText, {
							tagName: 'p',
							className: 'mwm-hero__lead',
							value: attributes.lead || '',
							placeholder: __( 'Descripcion', 'mwm-blocks' ),
							onChange: function ( value ) {
								setAttributes( { lead: value } );
							},
						} ),
						el(
							'div',
							{ className: 'mwm-hero__actions' },
							el(
								'a',
								{
									href: attributes.primaryUrl || '#',
									className: 'mwm-btn mwm-btn--primary mwm-btn--lg',
								},
								attributes.primaryText || __( 'Boton primario', 'mwm-blocks' )
							),
							el(
								'a',
								{
									href: attributes.secondaryUrl || '#',
									className: 'mwm-btn mwm-btn--ghost mwm-btn--lg',
								},
								attributes.secondaryText || __( 'Boton secundario', 'mwm-blocks' )
							)
						)
					),
					el(
						'div',
						{ className: 'mwm-hero__visual' },
						attributes.imageUrl
							? el( 'img', {
									src: attributes.imageUrl,
									alt: attributes.imageAlt || '',
							  } )
							: el( 'p', null, __( 'Selecciona una imagen desde el panel lateral.', 'mwm-blocks' ) )
					)
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/hero-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/hero' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderHeroEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
