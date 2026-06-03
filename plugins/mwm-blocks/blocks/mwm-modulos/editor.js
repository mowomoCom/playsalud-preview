( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, Button, TextareaControl } = wp.components;
	const { InspectorControls, useBlockProps, MediaUpload, MediaUploadCheck } = wp.blockEditor;

	function normalizeItem( item ) {
		const safeItem = item && typeof item === 'object' ? item : {};
		const legacyCaption = safeItem.meta || '';
		return {
			imageId: safeItem.imageId || 0,
			imageUrl: safeItem.imageUrl || '',
			caption: safeItem.caption || legacyCaption,
			title: safeItem.title || '',
			description: safeItem.description || '',
		};
	}

	function normalizeItems( items ) {
		return Array.isArray( items ) ? items.map( normalizeItem ) : [];
	}

	function createEmptyItem() {
		return {
			imageId: 0,
			imageUrl: '',
			caption: '',
			title: '',
			description: '',
		};
	}

	function renderModulosEdit( props ) {
		const { attributes, setAttributes } = props;
		const items = normalizeItems( attributes.items );
		const blockProps = useBlockProps( { className: 'mwm-modulos-editor' } );

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
						label: __( 'Descripcion de seccion', 'mwm-blocks' ),
						value: attributes.sectionDescription || '',
						onChange: function ( value ) {
							setAttributes( { sectionDescription: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Boton de seccion', 'mwm-blocks' ), initialOpen: false },
					el( TextControl, {
						label: __( 'Texto del boton', 'mwm-blocks' ),
						value: attributes.buttonLabel || '',
						onChange: function ( value ) {
							setAttributes( { buttonLabel: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'URL del boton', 'mwm-blocks' ),
						value: attributes.buttonUrl || '',
						onChange: function ( value ) {
							setAttributes( { buttonUrl: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Tarjetas', 'mwm-blocks' ), initialOpen: true },
					items.map( function ( item, index ) {
						return el(
							'div',
							{
								key: index,
								style: {
									marginBottom: '16px',
									paddingBottom: '16px',
									borderBottom: '1px solid #ddd',
								},
							},
							el( 'p', { style: { fontWeight: 600, margin: '0 0 8px' } }, __( 'Tarjeta', 'mwm-blocks' ) + ' ' + ( index + 1 ) ),
							el(
								MediaUploadCheck,
								null,
								el( MediaUpload, {
									onSelect: function ( media ) {
										updateItem( index, {
											imageId: media && media.id ? media.id : 0,
											imageUrl: media && media.url ? media.url : '',
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
											item.imageUrl &&
												el(
													Button,
													{
														variant: 'link',
														isDestructive: true,
														onClick: function () {
															updateItem( index, { imageId: 0, imageUrl: '' } );
														},
													},
													__( 'Quitar imagen', 'mwm-blocks' )
												)
										);
									},
								} )
							),
							el( TextControl, {
								label: __( 'Caption', 'mwm-blocks' ),
								value: item.caption,
								onChange: function ( value ) {
									updateItem( index, { caption: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Titulo', 'mwm-blocks' ),
								value: item.title,
								onChange: function ( value ) {
									updateItem( index, { title: value } );
								},
							} ),
							el( TextareaControl, {
								label: __( 'Descripcion', 'mwm-blocks' ),
								value: item.description,
								onChange: function ( value ) {
									updateItem( index, { description: value } );
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
								__( 'Eliminar tarjeta', 'mwm-blocks' )
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
						__( 'Agregar tarjeta', 'mwm-blocks' )
					)
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
						{ className: 'mwm-modulos__section-title' },
						el( 'span', { className: 'mwm-modulos__title-light' }, attributes.titleLight || '' ),
						el( 'br' ),
						el( 'span', { className: 'mwm-modulos__title-bold' }, attributes.titleBold || attributes.title || '' )
					),
					el( 'p', { className: 'mwm-modulos__section-desc' }, attributes.sectionDescription || '' ),
					el(
						'div',
						{ className: 'mwm-modulos__grid' },
						items.map( function ( item, index ) {
							return el(
								'article',
								{ key: index, className: 'mwm-modulos__card' },
								el(
									'div',
									{ className: 'mwm-modulos__photo' },
									item.caption ? el( 'span', { className: 'mwm-modulos__badge' }, item.caption ) : null,
									item.imageUrl
										? el( 'img', {
												src: item.imageUrl,
												alt: item.title || '',
										  } )
										: el(
												'div',
												{ className: 'mwm-modulos__photo-placeholder' },
												__( 'Sin imagen', 'mwm-blocks' )
										  )
								),
								el(
									'div',
									{ className: 'mwm-modulos__body' },
									el( 'h3', { className: 'mwm-modulos__title' }, item.title || '' ),
									el( 'p', { className: 'mwm-modulos__desc' }, item.description || '' )
								)
							);
						} )
					),
					attributes.buttonLabel
						? el(
								'div',
								{ className: 'mwm-modulos__cta-row' },
								el(
									'a',
									{
										className: 'mwm-modulos__cta',
										href: attributes.buttonUrl || '#',
									},
									attributes.buttonLabel
								)
						  )
						: null
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/modulos-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/modulos' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderModulosEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
