( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, TextareaControl, BaseControl, Button } = wp.components;
	const { InspectorControls, URLInputButton, useBlockProps, MediaUpload, MediaUploadCheck } = wp.blockEditor;

	function renderArrowIcon() {
		return el(
			'svg',
			{
				width: '14',
				height: '14',
				viewBox: '0 0 16 16',
				fill: 'none',
				'aria-hidden': 'true',
			},
			el( 'path', {
				d: 'M3 8h10m-4-4l4 4-4 4',
				stroke: 'currentColor',
				'stroke-width': '2',
				'stroke-linecap': 'round',
				'stroke-linejoin': 'round',
			} )
		);
	}

	function renderMediaControl( label, imageId, imageUrl, imageAlt, setAttributes, keyPrefix ) {
		return el(
			BaseControl,
			{ label: label },
			el( MediaUploadCheck, null, [
				el( MediaUpload, {
					key: keyPrefix + '-upload',
					onSelect: function ( media ) {
						setAttributes( {
							[ keyPrefix + 'ImageId' ]: media && media.id ? media.id : 0,
							[ keyPrefix + 'ImageUrl' ]: media && media.url ? media.url : '',
							[ keyPrefix + 'ImageAlt' ]: media && media.alt ? media.alt : '',
						} );
					},
					allowedTypes: [ 'image' ],
					value: imageId || 0,
					render: function ( mediaProps ) {
						return el(
							Button,
							{
								variant: 'secondary',
								onClick: mediaProps.open,
							},
							imageUrl ? __( 'Reemplazar imagen', 'mwm-blocks' ) : __( 'Seleccionar imagen', 'mwm-blocks' )
						);
					},
				} ),
				imageUrl
					? el(
							Button,
							{
								key: keyPrefix + '-remove',
								variant: 'link',
								isDestructive: true,
								onClick: function () {
									setAttributes( {
										[ keyPrefix + 'ImageId' ]: 0,
										[ keyPrefix + 'ImageUrl' ]: '',
										[ keyPrefix + 'ImageAlt' ]: '',
									} );
								},
							},
							__( 'Quitar imagen', 'mwm-blocks' )
					  )
					: null,
				el( TextControl, {
					key: keyPrefix + '-alt',
					label: __( 'Texto alternativo', 'mwm-blocks' ),
					value: imageAlt || '',
					onChange: function ( value ) {
						setAttributes( { [ keyPrefix + 'ImageAlt' ]: value } );
					},
				} ),
			] )
		);
	}

	function renderCardPreview( config ) {
		return el(
			'article',
			{ className: 'mwm-verticales__card' },
			el(
				'div',
				{ className: 'mwm-verticales__photo-wrap' },
				el( 'span', { className: 'mwm-verticales__photo-tag ' + config.tagClass }, config.tag ),
				el(
					'picture',
					{ className: 'mwm-verticales__photo' },
					el( 'img', {
						src: config.imageUrl || 'https://placehold.co/960x540/e2e8f0/334155?text=PlaySalud',
						alt: config.imageAlt || '',
					} )
				)
			),
			el(
				'div',
				{ className: 'mwm-verticales__content' },
				el( 'h3', { className: 'mwm-verticales__card-title' }, config.title ),
				el( 'p', { className: 'mwm-verticales__card-desc' }, config.text ),
				el(
					'div',
					{ className: 'mwm-verticales__cta-row' },
					el(
						'a',
						{ href: config.primaryUrl || '#', className: 'mwm-btn ' + config.primaryClass + ' mwm-btn--md' },
						[ config.primaryText, renderArrowIcon() ]
					),
					el(
						'a',
						{ href: config.secondaryUrl || '#', className: 'mwm-btn mwm-btn--ghost mwm-btn--md' },
						config.secondaryText
					)
				)
			)
		);
	}

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
					} ),
					el( TextareaControl, {
						label: __( 'Subtitulo', 'mwm-blocks' ),
						value: attributes.subtitle || '',
						onChange: function ( value ) {
							setAttributes( { subtitle: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Card PlayCare', 'mwm-blocks' ), initialOpen: true },
					el( TextControl, {
						label: __( 'Tag de imagen', 'mwm-blocks' ),
						value: attributes.careTag || '',
						onChange: function ( value ) {
							setAttributes( { careTag: value } );
						},
					} ),
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
					renderMediaControl(
						__( 'Imagen PlayCare', 'mwm-blocks' ),
						attributes.careImageId,
						attributes.careImageUrl,
						attributes.careImageAlt,
						setAttributes,
						'care'
					),
					el( TextControl, {
						label: __( 'Texto boton principal', 'mwm-blocks' ),
						value: attributes.careButtonText || '',
						onChange: function ( value ) {
							setAttributes( { careButtonText: value } );
						},
					} ),
					el( URLInputButton, {
						label: __( 'URL boton principal', 'mwm-blocks' ),
						url: attributes.careButtonUrl || '',
						onChange: function ( value ) {
							setAttributes( { careButtonUrl: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Texto boton secundario', 'mwm-blocks' ),
						value: attributes.careSecondaryButtonText || '',
						onChange: function ( value ) {
							setAttributes( { careSecondaryButtonText: value } );
						},
					} ),
					el( URLInputButton, {
						label: __( 'URL boton secundario', 'mwm-blocks' ),
						url: attributes.careSecondaryButtonUrl || '',
						onChange: function ( value ) {
							setAttributes( { careSecondaryButtonUrl: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Card PlayAcademy', 'mwm-blocks' ), initialOpen: false },
					el( TextControl, {
						label: __( 'Tag de imagen', 'mwm-blocks' ),
						value: attributes.academyTag || '',
						onChange: function ( value ) {
							setAttributes( { academyTag: value } );
						},
					} ),
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
					renderMediaControl(
						__( 'Imagen PlayAcademy', 'mwm-blocks' ),
						attributes.academyImageId,
						attributes.academyImageUrl,
						attributes.academyImageAlt,
						setAttributes,
						'academy'
					),
					el( TextControl, {
						label: __( 'Texto boton principal', 'mwm-blocks' ),
						value: attributes.academyButtonText || '',
						onChange: function ( value ) {
							setAttributes( { academyButtonText: value } );
						},
					} ),
					el( URLInputButton, {
						label: __( 'URL boton principal', 'mwm-blocks' ),
						url: attributes.academyButtonUrl || '',
						onChange: function ( value ) {
							setAttributes( { academyButtonUrl: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Texto boton secundario', 'mwm-blocks' ),
						value: attributes.academySecondaryButtonText || '',
						onChange: function ( value ) {
							setAttributes( { academySecondaryButtonText: value } );
						},
					} ),
					el( URLInputButton, {
						label: __( 'URL boton secundario', 'mwm-blocks' ),
						url: attributes.academySecondaryButtonUrl || '',
						onChange: function ( value ) {
							setAttributes( { academySecondaryButtonUrl: value } );
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
					el(
						'h2',
						{ className: 'mwm-verticales__title' },
						el( 'span', { className: 't-bold accent' }, attributes.careTag || 'PlayCare' ),
						' ',
						el( 'span', { className: 't-thin' }, 'y' ),
						' ',
						el( 'span', { className: 't-bold accent-orange' }, attributes.academyTag || 'PlayAcademy' ),
						'.'
					),
					el( 'p', { className: 'mwm-verticales__subtitle' }, attributes.subtitle || '' ),
					el(
						'div',
						{ className: 'mwm-verticales__grid' },
						renderCardPreview( {
							tagClass: 'is-care',
							tag: attributes.careTag || '',
							imageUrl: attributes.careImageUrl || '',
							imageAlt: attributes.careImageAlt || '',
							title: attributes.careTitle || '',
							text: attributes.careText || '',
							primaryText: attributes.careButtonText || '',
							primaryUrl: attributes.careButtonUrl || '#',
							primaryClass: 'mwm-btn--sky',
							secondaryText: attributes.careSecondaryButtonText || '',
							secondaryUrl: attributes.careSecondaryButtonUrl || '#',
						} ),
						renderCardPreview( {
							tagClass: 'is-academy',
							tag: attributes.academyTag || '',
							imageUrl: attributes.academyImageUrl || '',
							imageAlt: attributes.academyImageAlt || '',
							title: attributes.academyTitle || '',
							text: attributes.academyText || '',
							primaryText: attributes.academyButtonText || '',
							primaryUrl: attributes.academyButtonUrl || '#',
							primaryClass: 'mwm-btn--primary',
							secondaryText: attributes.academySecondaryButtonText || '',
							secondaryUrl: attributes.academySecondaryButtonUrl || '#',
						} )
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
