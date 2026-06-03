( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const {
		PanelBody,
		TextControl,
		TextareaControl,
		Button,
		CheckboxControl,
		ToggleControl,
		SelectControl,
	} = wp.components;
	const { InspectorControls, useBlockProps, MediaUpload, MediaUploadCheck } = wp.blockEditor;

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

	function chipsToString( chips ) {
		if ( ! Array.isArray( chips ) ) {
			return '';
		}

		return chips.join( ', ' );
	}

	function stringToChips( rawValue ) {
		return ( rawValue || '' )
			.split( ',' )
			.map( function ( value ) {
				return value.trim();
			} )
			.filter( function ( value ) {
				return value.length > 0;
			} );
	}

	function normalizeItem( item ) {
		const safeItem = item && typeof item === 'object' ? item : {};
		const legacyMeta = safeItem.meta || '';
		const chips = Array.isArray( safeItem.chips ) ? safeItem.chips : stringToChips( legacyMeta );

		return {
			status: safeItem.status || '',
			tag: safeItem.tag || '',
			title: safeItem.title || '',
			description: safeItem.description || legacyMeta,
			chips: chips,
			imageId: safeItem.imageId || 0,
			imageUrl: safeItem.imageUrl || '',
			imageAlt: safeItem.imageAlt || '',
			ctaLabel: safeItem.ctaLabel || '',
			ctaUrl: safeItem.ctaUrl || '#contacto',
			buttonStyle: safeItem.buttonStyle || 'ghost',
			featured: !! safeItem.featured,
			upcoming: !! safeItem.upcoming,
			showPlay: Object.prototype.hasOwnProperty.call( safeItem, 'showPlay' ) ? !! safeItem.showPlay : ! safeItem.upcoming,
		};
	}

	function normalizeItems( items ) {
		return Array.isArray( items ) ? items.map( normalizeItem ) : [];
	}

	function createEmptyItem() {
		return {
			status: '',
			tag: '',
			title: '',
			description: '',
			chips: [],
			imageId: 0,
			imageUrl: '',
			imageAlt: '',
			ctaLabel: '',
			ctaUrl: '#contacto',
			buttonStyle: 'ghost',
			featured: false,
			upcoming: false,
			showPlay: true,
		};
	}

	function renderCursosEdit( props ) {
		const { attributes, setAttributes } = props;
		const items = normalizeItems( attributes.items );
		const blockProps = useBlockProps( { className: 'mwm-cursos-editor' } );

		function updateItem( index, patch ) {
			const next = items.slice();
			next[ index ] = Object.assign( {}, next[ index ] || createEmptyItem(), patch );
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
						label: __( 'Titulo light', 'mwm-blocks' ),
						value: attributes.titleLight || '',
						onChange: function ( value ) {
							setAttributes( { titleLight: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Titulo bold', 'mwm-blocks' ),
						value: attributes.titleBold || '',
						onChange: function ( value ) {
							setAttributes( { titleBold: value } );
						},
					} ),
					el( TextareaControl, {
						label: __( 'Subtitulo', 'mwm-blocks' ),
						value: attributes.sectionDescription || '',
						onChange: function ( value ) {
							setAttributes( { sectionDescription: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Boton final', 'mwm-blocks' ), initialOpen: false },
					el( TextControl, {
						label: __( 'Texto del boton', 'mwm-blocks' ),
						value: attributes.footerButtonLabel || '',
						onChange: function ( value ) {
							setAttributes( { footerButtonLabel: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'URL del boton', 'mwm-blocks' ),
						value: attributes.footerButtonUrl || '',
						onChange: function ( value ) {
							setAttributes( { footerButtonUrl: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Cursos', 'mwm-blocks' ), initialOpen: true },
					items.map( function ( item, index ) {
						return el(
							'div',
							{
								key: index,
								style: { marginBottom: '16px', paddingBottom: '12px', borderBottom: '1px solid #ddd' },
							},
							el( 'p', { style: { fontWeight: 600, margin: '0 0 8px' } }, __( 'Curso', 'mwm-blocks' ) + ' ' + ( index + 1 ) ),
							el(
								MediaUploadCheck,
								null,
								el( MediaUpload, {
									onSelect: function ( media ) {
										updateItem( index, {
											imageId: media && media.id ? media.id : 0,
											imageUrl: media && media.url ? media.url : '',
											imageAlt: media && media.alt ? media.alt : '',
										} );
									},
									allowedTypes: [ 'image' ],
									value: item.imageId || 0,
									render: function ( mediaProps ) {
										return el(
											Fragment,
											null,
											el(
												Button,
												{
													variant: 'secondary',
													onClick: mediaProps.open,
													style: { marginBottom: '8px' },
												},
												item.imageUrl ? __( 'Reemplazar imagen', 'mwm-blocks' ) : __( 'Seleccionar imagen', 'mwm-blocks' )
											),
											item.imageUrl
												? el(
														Button,
														{
															variant: 'link',
															isDestructive: true,
															onClick: function () {
																updateItem( index, { imageId: 0, imageUrl: '', imageAlt: '' } );
															},
														},
														__( 'Quitar imagen', 'mwm-blocks' )
												  )
												: null
										);
									},
								} )
							),
							el( TextControl, {
								label: __( 'Texto alternativo de imagen', 'mwm-blocks' ),
								value: item.imageAlt || '',
								onChange: function ( value ) {
									updateItem( index, { imageAlt: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Estado', 'mwm-blocks' ),
								value: item.status || '',
								onChange: function ( value ) {
									updateItem( index, { status: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Tag', 'mwm-blocks' ),
								value: item.tag || '',
								onChange: function ( value ) {
									updateItem( index, { tag: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Titulo', 'mwm-blocks' ),
								value: item.title || '',
								onChange: function ( value ) {
									updateItem( index, { title: value } );
								},
							} ),
							el( TextareaControl, {
								label: __( 'Descripcion', 'mwm-blocks' ),
								value: item.description || '',
								onChange: function ( value ) {
									updateItem( index, { description: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Chips (separados por coma)', 'mwm-blocks' ),
								value: chipsToString( item.chips ),
								onChange: function ( value ) {
									updateItem( index, { chips: stringToChips( value ) } );
								},
							} ),
							el( CheckboxControl, {
								label: __( 'Destacado', 'mwm-blocks' ),
								checked: !! item.featured,
								onChange: function ( checked ) {
									updateItem( index, { featured: !! checked } );
								},
							} ),
							el( ToggleControl, {
								label: __( 'Proximamente', 'mwm-blocks' ),
								checked: !! item.upcoming,
								onChange: function ( checked ) {
									updateItem( index, { upcoming: !! checked } );
								},
							} ),
							el( ToggleControl, {
								label: __( 'Mostrar icono play', 'mwm-blocks' ),
								checked: !! item.showPlay,
								onChange: function ( checked ) {
									updateItem( index, { showPlay: !! checked } );
								},
							} ),
							el( SelectControl, {
								label: __( 'Estilo de boton', 'mwm-blocks' ),
								value: item.buttonStyle || 'ghost',
								options: [
									{ label: __( 'Primario', 'mwm-blocks' ), value: 'primary' },
									{ label: __( 'Ghost', 'mwm-blocks' ), value: 'ghost' },
								],
								onChange: function ( value ) {
									updateItem( index, { buttonStyle: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Texto CTA', 'mwm-blocks' ),
								value: item.ctaLabel || '',
								onChange: function ( value ) {
									updateItem( index, { ctaLabel: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'URL CTA', 'mwm-blocks' ),
								value: item.ctaUrl || '',
								onChange: function ( value ) {
									updateItem( index, { ctaUrl: value } );
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
								__( 'Eliminar curso', 'mwm-blocks' )
							)
						);
					} ),
					el(
						Button,
						{
							variant: 'secondary',
							onClick: function () {
								setAttributes( {
									items: items.concat( [ createEmptyItem() ] ),
								} );
							},
						},
						__( 'Agregar curso', 'mwm-blocks' )
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
						{ className: 'mwm-cursos__header' },
						el( 'p', { className: 'mwm-eyebrow mwm-cursos__eyebrow mwm-cursos__eyebrow--academy' }, attributes.eyebrow || '' ),
						el(
							'h2',
							{ className: 'mwm-cursos__section-title' },
							el( 'span', { className: 'mwm-cursos__title-light' }, attributes.titleLight || '' ),
							el( 'br' ),
							el( 'span', { className: 'mwm-cursos__title-bold' }, attributes.titleBold || '' )
						),
						el( 'p', { className: 'mwm-cursos__section-desc' }, attributes.sectionDescription || '' )
					),
					el(
						'div',
						{ className: 'mwm-cursos__grid' },
						items.map( function ( item, index ) {
							const classes =
								'mwm-cursos__card' +
								( item && item.featured ? ' mwm-cursos__card--featured' : '' ) +
								( item && item.upcoming ? ' mwm-cursos__card--upcoming' : '' );
							const buttonClass =
								'mwm-btn mwm-btn--md ' + ( item && item.buttonStyle === 'primary' ? 'mwm-btn--primary' : 'mwm-btn--ghost' );

							return el(
								'article',
								{ key: index, className: classes },
								el(
									'div',
									{ className: 'mwm-cursos__media' },
									item && item.status ? el( 'span', { className: 'mwm-cursos__status' + ( item.upcoming ? ' is-upcoming' : '' ) }, item.status ) : null,
									item && item.imageUrl
										? el( 'img', {
												src: item.imageUrl,
												alt: item.imageAlt || '',
										  } )
										: el( 'div', { className: 'mwm-cursos__media-placeholder' }, __( 'Sin imagen', 'mwm-blocks' ) ),
									item && item.showPlay
										? el(
												'div',
												{ className: 'mwm-cursos__play', 'aria-hidden': 'true' },
												el(
													'svg',
													{ viewBox: '0 0 24 24', fill: 'currentColor' },
													el( 'path', { d: 'M8 5v14l11-7z' } )
												)
										  )
										: null
								),
								el(
									'div',
									{ className: 'mwm-cursos__body' },
									item && item.tag ? el( 'span', { className: 'mwm-cursos__tag' }, item.tag ) : null,
									el( 'h3', { className: 'mwm-cursos__title' }, item && item.title ? item.title : '' ),
									item && item.description ? el( 'p', { className: 'mwm-cursos__desc' }, item.description ) : null,
									item && item.chips && item.chips.length
										? el(
												'div',
												{ className: 'mwm-cursos__meta' },
												item.chips.map( function ( chip, chipIndex ) {
													return el( 'span', { key: chipIndex, className: 'mwm-cursos__chip' }, chip );
												} )
										  )
										: null,
									item && item.ctaLabel
										? el(
												'div',
												{ className: 'mwm-cursos__cta' },
												el(
													'a',
													{
														href: item.ctaUrl || '#',
														className: buttonClass,
													},
													[ item.ctaLabel, renderArrowIcon() ]
												)
										  )
										: null
								)
							);
						} )
					),
					attributes.footerButtonLabel
						? el(
								'div',
								{ className: 'mwm-cursos__footer-cta' },
								el(
									'a',
									{
										className: 'mwm-btn mwm-btn--sky mwm-btn--md',
										href: attributes.footerButtonUrl || '#',
									},
									[ attributes.footerButtonLabel, renderArrowIcon() ]
								)
						  )
						: null
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/cursos-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/cursos' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderCursosEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
