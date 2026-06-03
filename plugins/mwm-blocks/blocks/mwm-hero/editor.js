( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment, useEffect } = wp.element;
	const {
		PanelBody,
		Button,
		TextControl,
		TextareaControl,
		SelectControl,
		ToggleControl,
	} = wp.components;
	const {
		InspectorControls,
		MediaUpload,
		MediaUploadCheck,
		RichText,
		URLInputButton,
		useBlockProps,
	} = wp.blockEditor;

	const TITLE_STYLE_OPTIONS = [
		{ label: __( 'Negrita', 'mwm-blocks' ), value: 't-bold' },
		{ label: __( 'Ligera', 'mwm-blocks' ), value: 't-thin' },
		{ label: __( 'Ligera + Accent', 'mwm-blocks' ), value: 't-thin accent' },
	];

	function getDefaultTitleLines() {
		return [
			{ text: 'Salud en video.', className: 't-bold' },
			{ text: 'Seria cuando toca,', className: 't-thin accent' },
			{ text: 'cercana cuando ayuda.', className: 't-thin' },
		];
	}

	function normalizeTitleLines( titleLines, legacyTitle ) {
		const defaults = getDefaultTitleLines();
		let lines = Array.isArray( titleLines ) ? titleLines : [];

		if ( ! lines.length && legacyTitle ) {
			lines = legacyTitle
				.split( /\r?\n/ )
				.join( ' ' )
				.split( /(?<=[\.\!\?])\s+/ )
				.filter( Boolean )
				.slice( 0, 5 )
				.map( function ( text, index ) {
					const fallback = defaults[ index ] || defaults[ defaults.length - 1 ];
					return {
						text: text.trim(),
						className: fallback.className,
					};
				} );
		}

		if ( ! lines.length ) {
			lines = defaults;
		}

		return lines.slice( 0, 5 ).map( function ( line, index ) {
			const fallback = defaults[ index ] || defaults[ defaults.length - 1 ];
			return {
				text: line && line.text ? line.text : fallback.text,
				className: line && line.className ? line.className : fallback.className,
			};
		} );
	}

	function renderHeroEdit( props ) {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'mwm-hero-editor' } );

		const normalizedLines = normalizeTitleLines( attributes.titleLines, attributes.title );
		const requestedCount = parseInt( attributes.titleLineCount, 10 );
		const titleLineCount = Number.isFinite( requestedCount )
			? Math.min( 5, Math.max( 1, requestedCount ) )
			: normalizedLines.length;
		const visibleTitleLines = normalizedLines.slice( 0, titleLineCount );

		useEffect(
			function () {
				const shouldUpdateLines =
					JSON.stringify( normalizedLines ) !== JSON.stringify( attributes.titleLines || [] );
				const shouldUpdateCount = attributes.titleLineCount !== titleLineCount;

				if ( shouldUpdateLines || shouldUpdateCount ) {
					setAttributes( {
						titleLines: normalizedLines,
						titleLineCount: titleLineCount,
					} );
				}
			},
			[ attributes.titleLines, attributes.titleLineCount, attributes.title, titleLineCount ]
		);

		function updateTitleLine( index, patch ) {
			const next = normalizedLines.slice( 0, titleLineCount );
			next[ index ] = Object.assign( {}, next[ index ] || {}, patch );
			setAttributes( { titleLines: next } );
		}

		const titlePreviewChildren = [];
		visibleTitleLines.forEach( function ( line, index ) {
			titlePreviewChildren.push(
				el(
					'span',
					{
						key: 'title-line-' + index,
						className: line.className || '',
					},
					line.text || ''
				)
			);
			if ( index < visibleTitleLines.length - 1 ) {
				titlePreviewChildren.push( el( 'br', { key: 'title-break-' + index } ) );
			}
		} );

		const trustDotsCount = Math.max( 1, Math.min( 5, parseInt( attributes.trustDotsCount, 10 ) || 3 ) );
		const trustDots = [];
		for ( let i = 0; i < trustDotsCount; i++ ) {
			trustDots.push( el( 'span', { key: 'dot-' + i, className: 'mwm-hero__trust-dot' } ) );
		}

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
					el( SelectControl, {
						label: __( 'Cantidad de lineas en H1', 'mwm-blocks' ),
						value: String( titleLineCount ),
						options: [
							{ label: '1', value: '1' },
							{ label: '2', value: '2' },
							{ label: '3', value: '3' },
							{ label: '4', value: '4' },
							{ label: '5', value: '5' },
						],
						onChange: function ( value ) {
							setAttributes( { titleLineCount: parseInt( value, 10 ) || 1 } );
						},
					} ),
					visibleTitleLines.map( function ( line, index ) {
						return el(
							Fragment,
							{ key: 'line-fields-' + index },
							el( TextControl, {
								label: __( 'Linea', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: line.text || '',
								onChange: function ( value ) {
									updateTitleLine( index, { text: value } );
								},
							} ),
							el( SelectControl, {
								label: __( 'Estilo de linea', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: line.className || 't-thin',
								options: TITLE_STYLE_OPTIONS,
								onChange: function ( value ) {
									updateTitleLine( index, { className: value } );
								},
							} )
						);
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
						initialOpen: false,
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
							setAttributes( { primaryUrl: value || '' } );
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
							setAttributes( { secondaryUrl: value || '' } );
						},
					} )
				),
				el(
					PanelBody,
					{
						title: __( 'Confianza y metadata', 'mwm-blocks' ),
						initialOpen: false,
					},
					el( ToggleControl, {
						label: __( 'Mostrar modulo de confianza', 'mwm-blocks' ),
						checked: !! attributes.showTrust,
						onChange: function ( value ) {
							setAttributes( { showTrust: value } );
						},
					} ),
					attributes.showTrust
						? el(
								Fragment,
								null,
								el( TextControl, {
									label: __( 'Texto de confianza', 'mwm-blocks' ),
									value: attributes.trustText || '',
									onChange: function ( value ) {
										setAttributes( { trustText: value } );
									},
								} ),
								el( SelectControl, {
									label: __( 'Cantidad de dots', 'mwm-blocks' ),
									value: String( trustDotsCount ),
									options: [
										{ label: '1', value: '1' },
										{ label: '2', value: '2' },
										{ label: '3', value: '3' },
										{ label: '4', value: '4' },
										{ label: '5', value: '5' },
									],
									onChange: function ( value ) {
										setAttributes( { trustDotsCount: parseInt( value, 10 ) || 3 } );
									},
								} )
						  )
						: null,
					el( ToggleControl, {
						label: __( 'Mostrar metadata de la foto', 'mwm-blocks' ),
						checked: !! attributes.showPhotoMeta,
						onChange: function ( value ) {
							setAttributes( { showPhotoMeta: value } );
						},
					} ),
					attributes.showPhotoMeta
						? el(
								Fragment,
								null,
								el( TextControl, {
									label: __( 'Tag de metadata', 'mwm-blocks' ),
									value: attributes.photoMetaTag || '',
									onChange: function ( value ) {
										setAttributes( { photoMetaTag: value } );
									},
								} ),
								el( TextControl, {
									label: __( 'Tiempo de metadata', 'mwm-blocks' ),
									value: attributes.photoMetaTime || '',
									onChange: function ( value ) {
										setAttributes( { photoMetaTime: value } );
									},
								} )
						  )
						: null
				),
				el(
					PanelBody,
					{
						title: __( 'Tarjetas flotantes', 'mwm-blocks' ),
						initialOpen: false,
					},
					el( ToggleControl, {
						label: __( 'Mostrar tarjeta PlayCare', 'mwm-blocks' ),
						checked: !! attributes.showFloatCare,
						onChange: function ( value ) {
							setAttributes( { showFloatCare: value } );
						},
					} ),
					attributes.showFloatCare
						? el(
								Fragment,
								null,
								el( TextControl, {
									label: __( 'PlayCare - Label', 'mwm-blocks' ),
									value: attributes.floatCareLabel || '',
									onChange: function ( value ) {
										setAttributes( { floatCareLabel: value } );
									},
								} ),
								el( TextControl, {
									label: __( 'PlayCare - Valor', 'mwm-blocks' ),
									value: attributes.floatCareValue || '',
									onChange: function ( value ) {
										setAttributes( { floatCareValue: value } );
									},
								} ),
								el( TextControl, {
									label: __( 'PlayCare - Subtexto', 'mwm-blocks' ),
									value: attributes.floatCareSub || '',
									onChange: function ( value ) {
										setAttributes( { floatCareSub: value } );
									},
								} )
						  )
						: null,
					el( ToggleControl, {
						label: __( 'Mostrar tarjeta PlayAcademy', 'mwm-blocks' ),
						checked: !! attributes.showFloatAcademy,
						onChange: function ( value ) {
							setAttributes( { showFloatAcademy: value } );
						},
					} ),
					attributes.showFloatAcademy
						? el(
								Fragment,
								null,
								el( TextControl, {
									label: __( 'PlayAcademy - Label', 'mwm-blocks' ),
									value: attributes.floatAcademyLabel || '',
									onChange: function ( value ) {
										setAttributes( { floatAcademyLabel: value } );
									},
								} ),
								el( TextControl, {
									label: __( 'PlayAcademy - Valor', 'mwm-blocks' ),
									value: attributes.floatAcademyValue || '',
									onChange: function ( value ) {
										setAttributes( { floatAcademyValue: value } );
									},
								} ),
								el( TextControl, {
									label: __( 'PlayAcademy - Subtexto', 'mwm-blocks' ),
									value: attributes.floatAcademySub || '',
									onChange: function ( value ) {
										setAttributes( { floatAcademySub: value } );
									},
								} )
						  )
						: null
				),
				el(
					PanelBody,
					{
						title: __( 'Imagen', 'mwm-blocks' ),
						initialOpen: false,
					},
					el( TextControl, {
						label: __( 'Texto alternativo', 'mwm-blocks' ),
						value: attributes.imageAlt || '',
						onChange: function ( value ) {
							setAttributes( { imageAlt: value } );
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
				)
			),
			el(
				'section',
				blockProps,
				el(
					'div',
					{ className: 'mwm-container mwm-hero__inner' },
					el(
						'div',
						{ className: 'mwm-hero__content' },
						el(
							'p',
							{ className: 'mwm-hero__badge' },
							el( 'span', { className: 'mwm-hero__badge-dot' } ),
							el( RichText, {
								tagName: 'span',
								withoutInteractiveFormatting: true,
								value: attributes.eyebrow || '',
								placeholder: __( 'Eyebrow', 'mwm-blocks' ),
								onChange: function ( value ) {
									setAttributes( { eyebrow: value } );
								},
							} )
						),
						el(
							'h1',
							{ className: 'mwm-hero__title' },
							titlePreviewChildren
						),
						el( RichText, {
							tagName: 'p',
							className: 'mwm-hero__lead',
							value: attributes.lead || '',
							placeholder: __( 'Descripcion principal', 'mwm-blocks' ),
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
						),
						attributes.showTrust
							? el(
									'div',
									{ className: 'mwm-hero__trust' },
									el( 'div', { className: 'mwm-hero__trust-dots' }, trustDots ),
									el( 'span', null, attributes.trustText || '' )
							  )
							: null
					),
					el(
						'div',
						{ className: 'mwm-hero__visual' },
						attributes.showFloatCare
							? el(
									'div',
									{ className: 'mwm-hero__float-card mwm-hero__float-care' },
									el( 'div', { className: 'mwm-hero__float-label' }, attributes.floatCareLabel || '' ),
									el( 'div', { className: 'mwm-hero__float-value' }, attributes.floatCareValue || '' ),
									el( 'div', { className: 'mwm-hero__float-sub' }, attributes.floatCareSub || '' )
							  )
							: null,
						el(
							'div',
							{ className: 'mwm-hero__photo-wrap' },
							attributes.imageUrl
								? el( 'img', {
										src: attributes.imageUrl,
										alt: attributes.imageAlt || '',
								  } )
								: el( 'div', { className: 'mwm-hero__photo-placeholder' }, __( 'Selecciona una imagen desde el panel lateral.', 'mwm-blocks' ) ),
							el( 'div', { className: 'mwm-hero__photo-overlay' } ),
							attributes.showPhotoMeta
								? el(
										'div',
										{ className: 'mwm-hero__photo-meta' },
										el( 'span', { className: 'mwm-hero__photo-meta-tag' }, attributes.photoMetaTag || '' ),
										el( 'span', { className: 'mwm-hero__photo-meta-dot' } ),
										el( 'span', { className: 'mwm-hero__photo-meta-time' }, attributes.photoMetaTime || '' )
								  )
								: null
						),
						attributes.showFloatAcademy
							? el(
									'div',
									{ className: 'mwm-hero__float-card mwm-hero__float-academy' },
									el( 'div', { className: 'mwm-hero__float-label' }, attributes.floatAcademyLabel || '' ),
									el( 'div', { className: 'mwm-hero__float-value' }, attributes.floatAcademyValue || '' ),
									el( 'div', { className: 'mwm-hero__float-sub' }, attributes.floatAcademySub || '' )
							  )
							: null
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
