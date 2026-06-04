( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, TextareaControl, Button } = wp.components;
	const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } = wp.blockEditor;

	function normalizeItems( items ) {
		return Array.isArray( items ) ? items : [];
	}

	function renderMuestraEdit( props ) {
		const { attributes, setAttributes } = props;
		const items = normalizeItems( attributes.items );
		const blockProps = useBlockProps( { className: 'mwm-muestra-editor' } );

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

		function getItemImageUrl( item ) {
			if ( item && item.imageUrl ) {
				return item.imageUrl;
			}

			if ( item && item.image && item.image.url ) {
				return item.image.url;
			}

			return '';
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
						label: __( 'Titulo (parte light)', 'mwm-blocks' ),
						value: attributes.titleLight || '',
						onChange: function ( value ) {
							setAttributes( { titleLight: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Titulo (parte bold)', 'mwm-blocks' ),
						value: attributes.titleBold || '',
						onChange: function ( value ) {
							setAttributes( { titleBold: value } );
						},
					} ),
					el( TextareaControl, {
						label: __( 'Descripcion', 'mwm-blocks' ),
						value: attributes.description || '',
						onChange: function ( value ) {
							setAttributes( { description: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Boton anterior', 'mwm-blocks' ),
						value: attributes.prevLabel || '',
						onChange: function ( value ) {
							setAttributes( { prevLabel: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Boton siguiente', 'mwm-blocks' ),
						value: attributes.nextLabel || '',
						onChange: function ( value ) {
							setAttributes( { nextLabel: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Slides', 'mwm-blocks' ), initialOpen: true },
					items.map( function ( item, index ) {
						var imageUrl = getItemImageUrl( item );
						return el(
							'div',
							{ key: index, style: { marginBottom: '16px', paddingBottom: '12px', borderBottom: '1px solid #ddd' } },
							el( TextControl, {
								label: __( 'Titulo del slide', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: item && item.title ? item.title : '',
								onChange: function ( value ) {
									updateItem( index, { title: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Duracion', 'mwm-blocks' ),
								value: item && item.time ? item.time : '',
								onChange: function ( value ) {
									updateItem( index, { time: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Tag', 'mwm-blocks' ),
								value: item && item.tag ? item.tag : '',
								onChange: function ( value ) {
									updateItem( index, { tag: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Aria label del play', 'mwm-blocks' ),
								value: item && item.playAriaLabel ? item.playAriaLabel : '',
								onChange: function ( value ) {
									updateItem( index, { playAriaLabel: value } );
								},
							} ),
							el(
								MediaUploadCheck,
								null,
								el( MediaUpload, {
									onSelect: function ( media ) {
										updateItem( index, {
											imageId: media && media.id ? media.id : 0,
											imageAlt: media && media.alt ? media.alt : '',
											imageUrl: media && media.url ? media.url : '',
										} );
									},
									allowedTypes: [ 'image' ],
									value: item && item.imageId ? item.imageId : 0,
									render: function ( mediaProps ) {
										return el(
											Button,
											{
												onClick: mediaProps.open,
												variant: 'secondary',
											},
											item && item.imageId
												? __( 'Reemplazar imagen', 'mwm-blocks' )
												: __( 'Seleccionar imagen', 'mwm-blocks' )
										);
									},
								} )
							),
							item && item.imageId
								? el(
										Button,
										{
											variant: 'link',
											isDestructive: true,
											onClick: function () {
												updateItem( index, { imageId: 0, imageAlt: '', imageUrl: '' } );
											},
										},
										__( 'Quitar imagen', 'mwm-blocks' )
								  )
								: null,
							! imageUrl
								? el(
										'p',
										{ style: { margin: '8px 0 0', fontSize: '12px', color: '#666' } },
										__( 'Este slide requiere una imagen para mostrarse en frontend.', 'mwm-blocks' )
								  )
								: null,
							el(
								Button,
								{
									variant: 'link',
									isDestructive: true,
									onClick: function () {
										removeItem( index );
									},
								},
								__( 'Eliminar slide', 'mwm-blocks' )
							)
						);
					} ),
					el(
						Button,
						{
							variant: 'secondary',
							onClick: function () {
								setAttributes( {
									items: items.concat( [
										{
											title: '',
											time: '',
											tag: '',
											playAriaLabel: '',
											imageId: 0,
											imageAlt: '',
											imageUrl: '',
										},
									] ),
								} );
							},
						},
						__( 'Agregar slide', 'mwm-blocks' )
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
						{ className: 'mwm-muestra__header' },
						el( 'p', { className: 'mwm-eyebrow mwm-muestra__eyebrow' }, attributes.eyebrow || '' ),
						el(
							'h2',
							{ className: 'mwm-muestra__title' },
							el( 'span', { className: 'mwm-muestra__title-light' }, attributes.titleLight || '' ),
							' ',
							el( 'span', { className: 'mwm-muestra__title-bold' }, attributes.titleBold || '' )
						),
						el( 'p', { className: 'mwm-muestra__description' }, attributes.description || '' )
					),
					el(
						'div',
						{ className: 'mwm-muestra__carousel' },
						items.map( function ( item, index ) {
							var imageUrl = getItemImageUrl( item );
							return el(
								'div',
								{ key: index, className: 'swiper-slide' },
								el(
									'div',
									{ className: 'mwm-muestra__slide' },
									el(
										'div',
										{
											className: 'mwm-muestra__player',
											role: 'button',
											tabIndex: 0,
											'aria-label':
												item && item.playAriaLabel
													? item.playAriaLabel
													: __( 'Reproducir capitulo', 'mwm-blocks' ),
										},
										imageUrl
											? el(
													'figure',
													{ className: 'mwm-muestra__slide-image' },
													el( 'img', {
														src: imageUrl,
														alt: item && item.imageAlt ? item.imageAlt : item && item.title ? item.title : '',
													} )
											  )
											: null,
										el(
											'div',
											{ className: 'mwm-muestra__play' },
											el(
												'svg',
												{ viewBox: '0 0 24 24', fill: 'currentColor' },
												el( 'path', { d: 'M8 5v14l11-7z' } )
											)
										)
									),
									el(
										'div',
										{ className: 'mwm-muestra__meta-row' },
										el( 'span', { className: 'mwm-muestra__video-title' }, item && item.title ? item.title : '' ),
										el( 'span', { className: 'mwm-muestra__meta-dot' } ),
										el( 'span', { className: 'mwm-muestra__time' }, item && item.time ? item.time : '' ),
										el( 'span', { className: 'mwm-muestra__meta-dot' } ),
										el( 'span', { className: 'mwm-muestra__tag' }, item && item.tag ? item.tag : '' )
									)
								)
							);
						} )
					)
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/muestra-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/muestra' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderMuestraEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
