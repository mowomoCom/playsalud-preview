( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, TextareaControl, Button } = wp.components;
	const { InspectorControls, useBlockProps, MediaUpload, MediaUploadCheck, URLInputButton } = wp.blockEditor;

	const LEGACY_DEFAULT_TITLE = 'Una plataforma para cada tipo de institucion.';

	function composeLegacyTitle( thin, bold, accent, fallback ) {
		const composed = [ thin, bold, accent ].filter( Boolean ).join( ' ' ).trim();
		return composed || fallback || '';
	}

	function resolveTitleParts( attributes ) {
		const thin = ( attributes.titlePartThin || '' ).trim();
		const bold = ( attributes.titlePartBold || '' ).trim();
		const accent = ( attributes.titleAccent || '' ).trim();

		if ( thin || bold || accent ) {
			return { thin: thin, bold: bold, accent: accent };
		}

		const legacyTitle = ( attributes.title || '' ).trim();

		if ( ! legacyTitle ) {
			return { thin: '', bold: '', accent: '' };
		}

		if ( legacyTitle === LEGACY_DEFAULT_TITLE ) {
			return {
				thin: 'Una plataforma para',
				bold: 'cada tipo de',
				accent: 'institucion.',
			};
		}

		return { thin: legacyTitle, bold: '', accent: '' };
	}

	function normalizeItems( items ) {
		if ( ! Array.isArray( items ) ) {
			return [];
		}

		return items.map( function ( item ) {
			const nextItem = item || {};
			return {
				title: nextItem.title || '',
				description: nextItem.description || '',
				iconUrl: nextItem.iconUrl || '',
				iconAlt: nextItem.iconAlt || '',
			};
		} );
	}

	function renderInstitucionesEdit( props ) {
		const { attributes, setAttributes } = props;
		const items = normalizeItems( attributes.items );
		const blockProps = useBlockProps( { className: 'mwm-home-section mwm-instituciones mwm-instituciones-editor' } );
		const titleParts = resolveTitleParts( attributes );

		function updateTitleParts( patch ) {
			const nextThin = Object.prototype.hasOwnProperty.call( patch, 'titlePartThin' ) ? patch.titlePartThin : titleParts.thin;
			const nextBold = Object.prototype.hasOwnProperty.call( patch, 'titlePartBold' ) ? patch.titlePartBold : titleParts.bold;
			const nextAccent = Object.prototype.hasOwnProperty.call( patch, 'titleAccent' ) ? patch.titleAccent : titleParts.accent;

			setAttributes(
				Object.assign( {}, patch, {
					title: composeLegacyTitle( nextThin, nextBold, nextAccent, attributes.title || '' ),
				} )
			);
		}

		function updateItem( index, patch ) {
			const next = items.slice();
			next[ index ] = Object.assign( {}, next[ index ] || {}, patch );
			setAttributes( { items: next } );
		}

		function removeItem( index ) {
			setAttributes( {
				items: items.filter( function ( _, idx ) {
					return idx !== index;
				} ),
			} );
		}

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
						value: titleParts.thin || '',
						onChange: function ( value ) {
							updateTitleParts( { titlePartThin: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Titulo destacado', 'mwm-blocks' ),
						value: titleParts.bold || '',
						onChange: function ( value ) {
							updateTitleParts( { titlePartBold: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Palabra/frase en accent', 'mwm-blocks' ),
						value: titleParts.accent || '',
						onChange: function ( value ) {
							updateTitleParts( { titleAccent: value } );
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
					{ title: __( 'Botones CTA', 'mwm-blocks' ), initialOpen: false },
					el( TextControl, {
						label: __( 'Texto boton principal', 'mwm-blocks' ),
						value: attributes.ctaPrimaryText || '',
						onChange: function ( value ) {
							setAttributes( { ctaPrimaryText: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'URL boton principal', 'mwm-blocks' ),
						value: attributes.ctaPrimaryUrl || '',
						onChange: function ( value ) {
							setAttributes( { ctaPrimaryUrl: value } );
						},
					} ),
					el( URLInputButton, {
						url: attributes.ctaPrimaryUrl || '',
						onChange: function ( value ) {
							setAttributes( { ctaPrimaryUrl: value || '' } );
						},
					} ),
					el( TextControl, {
						label: __( 'Texto boton secundario', 'mwm-blocks' ),
						value: attributes.ctaSecondaryText || '',
						onChange: function ( value ) {
							setAttributes( { ctaSecondaryText: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'URL boton secundario', 'mwm-blocks' ),
						value: attributes.ctaSecondaryUrl || '',
						onChange: function ( value ) {
							setAttributes( { ctaSecondaryUrl: value } );
						},
					} ),
					el( URLInputButton, {
						url: attributes.ctaSecondaryUrl || '',
						onChange: function ( value ) {
							setAttributes( { ctaSecondaryUrl: value || '' } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Instituciones', 'mwm-blocks' ), initialOpen: true },
					items.map( function ( item, index ) {
						return el(
							'div',
							{ key: index, style: { marginBottom: '16px', paddingBottom: '12px', borderBottom: '1px solid #ddd' } },
							el( TextControl, {
								label: __( 'Titulo', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: item && item.title ? item.title : '',
								onChange: function ( value ) {
									updateItem( index, { title: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Descripcion', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: item && item.description ? item.description : '',
								onChange: function ( value ) {
									updateItem( index, { description: value } );
								},
							} ),
							el(
								MediaUploadCheck,
								null,
								el( MediaUpload, {
									onSelect: function ( media ) {
										updateItem( index, {
											iconUrl: media && media.url ? media.url : '',
											iconAlt: media && media.alt ? media.alt : ( item && item.title ? item.title : '' ),
										} );
									},
									allowedTypes: [ 'image' ],
									value: item && item.iconUrl ? item.iconUrl : '',
									render: function ( mediaUploadProps ) {
										return el(
											Button,
											{
												variant: 'secondary',
												onClick: mediaUploadProps.open,
												style: { marginBottom: '8px' },
											},
											item && item.iconUrl
												? __( 'Cambiar icono (imagen)', 'mwm-blocks' )
												: __( 'Subir icono (imagen)', 'mwm-blocks' )
										);
									},
								} )
							),
							item && item.iconUrl
								? el(
										Fragment,
										null,
										el( 'img', {
											src: item.iconUrl,
											alt: item.iconAlt || '',
											style: {
												width: '52px',
												height: '52px',
												objectFit: 'cover',
												borderRadius: '8px',
												marginBottom: '8px',
												display: 'block',
											},
										} ),
										el(
											Button,
											{
												variant: 'link',
												isDestructive: true,
												onClick: function () {
													updateItem( index, { iconUrl: '', iconAlt: '' } );
												},
												style: { marginBottom: '8px' },
											},
											__( 'Quitar icono', 'mwm-blocks' )
										)
									)
								: null,
							el( TextControl, {
								label: __( 'Alt icono', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: item && item.iconAlt ? item.iconAlt : '',
								onChange: function ( value ) {
									updateItem( index, { iconAlt: value } );
								},
							} ),
							el(
								Button,
								{
									variant: 'link',
									isDestructive: true,
									onClick: function () {
										removeItem( index );
									},
								},
								__( 'Eliminar item', 'mwm-blocks' )
							)
						);
					} ),
					el(
						Button,
						{
							variant: 'secondary',
							onClick: function () {
								setAttributes( {
									items: items.concat( [ { title: '', description: '', iconUrl: '', iconAlt: '' } ] ),
								} );
							},
						},
						__( 'Agregar item', 'mwm-blocks' )
					)
				)
			),
			el(
				'section',
				blockProps,
				el(
					'div',
					{ className: 'mwm-container' },
					el(
						'div',
						{ className: 'section-header reveal' },
						el( 'div', { className: 'mwm-eyebrow mwm-eyebrow-instituciones' }, attributes.eyebrow || '' ),
						el(
							'h2',
							{ className: 'section-title mwm-instituciones__title' },
							titleParts.thin ? el( 'span', { className: 't-thin' }, titleParts.thin + ' ' ) : null,
							titleParts.bold ? el( 'span', { className: 't-bold' }, titleParts.bold ) : null,
							titleParts.accent ? el( 'span', { className: 'accent' }, ' ' + titleParts.accent ) : null,
							! titleParts.thin && ! titleParts.bold && ! titleParts.accent ? attributes.title || '' : null
						),
						el( 'p', { className: 'section-subtitle' }, attributes.subtitle || '' )
					),
					el(
						'div',
						{ className: 'inst-grid' },
						items.map( function ( item, index ) {
							return el(
								'article',
								{ key: index, className: 'inst-card reveal' },
								el(
									'div',
									{ className: 'inst-icon' },
									item && item.iconUrl
										? el( 'img', { src: item.iconUrl, alt: item.iconAlt || '' } )
										: null
								),
								el(
									'div',
									null,
									el( 'h3', { className: 'inst-title' }, item && item.title ? item.title : '' ),
									el( 'p', { className: 'inst-desc' }, item && item.description ? item.description : '' )
								)
							);
						} )
					),
					el(
						'div',
						{ className: 'inst-cta-row' },
						el(
							'a',
							{
								href: attributes.ctaPrimaryUrl || '#',
								className: 'mwm-btn mwm-btn--md mwm-btn--primary',
							},
							attributes.ctaPrimaryText || '',
							el(
								'svg',
								{ width: '16', height: '16', viewBox: '0 0 16 16', fill: 'none', 'aria-hidden': 'true', focusable: 'false' },
								el( 'path', {
									d: 'M3 8h10m-4-4l4 4-4 4',
									stroke: 'currentColor',
									'stroke-width': '2',
									'stroke-linecap': 'round',
									'stroke-linejoin': 'round',
								} )
							)
						),
						el(
							'a',
							{ href: attributes.ctaSecondaryUrl || '#', className: 'mwm-btn mwm-btn--ghost mwm-btn--md' },
							attributes.ctaSecondaryText || ''
						)
					)
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/instituciones-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/instituciones' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderInstitucionesEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
