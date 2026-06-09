( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment, useEffect, useRef } = wp.element;
	const { PanelBody, TextControl, TextareaControl, Button } = wp.components;
	const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } = wp.blockEditor;
	const apiFetch = wp.apiFetch;

	function normalizeItems( items ) {
		return Array.isArray( items ) ? items : [];
	}

	function parseVideoUrl( url ) {
		url = ( url || '' ).trim();

		if ( ! url ) {
			return null;
		}

		var youtubeMatch = url.match(
			/(?:youtube\.com\/(?:watch\?(?:[^&\s]+&)*v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i
		);
		if ( youtubeMatch ) {
			return {
				provider: 'youtube',
				id: youtubeMatch[ 1 ],
				url: 'https://www.youtube.com/watch?v=' + youtubeMatch[ 1 ],
			};
		}

		var vimeoMatch = url.match( /(?:player\.)?vimeo\.com\/(?:video\/)?(\d+)/i );
		if ( vimeoMatch ) {
			return {
				provider: 'vimeo',
				id: vimeoMatch[ 1 ],
				url: 'https://vimeo.com/' + vimeoMatch[ 1 ],
			};
		}

		return null;
	}

	function getPlayAriaLabel( title ) {
		title = ( title || '' ).trim();

		if ( ! title ) {
			return __( 'Reproducir capitulo', 'mwm-blocks' );
		}

		return __( 'Reproducir capitulo:', 'mwm-blocks' ) + ' ' + title;
	}

	function fetchAndSetPoster( index, url, item, updateItemFn ) {
		var parsed = parseVideoUrl( url );

		if ( ! parsed || ( item && item.imageId ) ) {
			return;
		}

		apiFetch( {
			path: '/mwm-blocks/v1/video-duration?url=' + encodeURIComponent( url.trim() ),
		} )
			.then( function ( response ) {
				if ( response && response.thumbnail ) {
					updateItemFn( index, { imageUrl: response.thumbnail } );
				}
			} )
			.catch( function () {} );
	}

	function getPosterHelpText( item ) {
		if ( item && item.imageId ) {
			return __( 'Poster personalizado.', 'mwm-blocks' );
		}

		if ( item && item.imageUrl ) {
			return __( 'Poster automatico. Selecciona una imagen para sobreescribirlo.', 'mwm-blocks' );
		}

		return __( 'Opcional con YouTube/Vimeo: se obtiene automaticamente al pegar el enlace.', 'mwm-blocks' );
	}

	function getSlideVideoSource( item ) {
		if ( item && item.videoFileId && item.videoFileUrl ) {
			return {
				type: 'html5',
				url: item.videoFileUrl,
			};
		}

		return parseVideoUrl( item && item.videoUrl ? item.videoUrl : '' );
	}

	function getVideoHelpText( item ) {
		var source = getSlideVideoSource( item );
		var parts = [];

		if ( source ) {
			if ( source.type === 'html5' ) {
				parts.push( __( 'Video subido desde WordPress', 'mwm-blocks' ) );
			} else {
				parts.push( __( 'Detectado:', 'mwm-blocks' ) + ' ' + source.provider );
			}
		} else if ( ( item && item.videoUrl ) || ( item && item.videoFileId ) ) {
			return __( 'Configura un video valido o un enlace de YouTube/Vimeo.', 'mwm-blocks' );
		}

		if ( item && item.time ) {
			parts.push( __( 'Duracion:', 'mwm-blocks' ) + ' ' + item.time );
		}

		if ( ! parts.length ) {
			return __( 'Sube un video o pega un enlace. Al hacer clic se abrira en popup.', 'mwm-blocks' );
		}

		return parts.join( ' · ' );
	}

	function renderMuestraEdit( props ) {
		const { attributes, setAttributes } = props;
		const items = normalizeItems( attributes.items );
		const blockProps = useBlockProps( { className: 'mwm-muestra-editor' } );
		const fetchedMetaRef = useRef( {} );

		function updateItem( index, patch ) {
			const next = items.slice();
			next[ index ] = Object.assign( {}, next[ index ] || {}, patch );
			setAttributes( { items: next } );
		}

		useEffect(
			function () {
				items.forEach( function ( item, index ) {
					if ( item && item.imageId ) {
						return;
					}

					var url = item && item.videoUrl ? item.videoUrl.trim() : '';

					if ( ! url ) {
						return;
					}

					var parsed = parseVideoUrl( url );

					if ( ! parsed ) {
						return;
					}

					var urlKey = parsed.provider + ':' + parsed.id;

					if ( fetchedMetaRef.current[ urlKey ] ) {
						return;
					}

					fetchedMetaRef.current[ urlKey ] = true;
					fetchAndSetPoster( index, url, item, updateItem );
				} );
			},
			[ attributes.items ]
		);

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
								label: __( 'Tag', 'mwm-blocks' ),
								value: item && item.tag ? item.tag : '',
								onChange: function ( value ) {
									updateItem( index, { tag: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Duracion', 'mwm-blocks' ),
								value: item && item.time ? item.time : '',
								help: __( 'Formato libre, por ejemplo 3:42.', 'mwm-blocks' ),
								onChange: function ( value ) {
									updateItem( index, { time: value } );
								},
							} ),
							el(
								'p',
								{ style: { margin: '0 0 4px', fontSize: '12px', fontWeight: 600 } },
								__( 'Poster del video', 'mwm-blocks' )
							),
							el(
								'p',
								{ style: { margin: '0 0 8px', fontSize: '12px', color: '#666' } },
								getPosterHelpText( item )
							),
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
												? __( 'Reemplazar poster', 'mwm-blocks' )
												: __( 'Seleccionar poster', 'mwm-blocks' )
										);
									},
								} )
							),
							item && ( item.imageId || item.imageUrl )
								? el(
										Button,
										{
											variant: 'link',
											isDestructive: true,
											onClick: function () {
												updateItem( index, { imageId: 0, imageAlt: '', imageUrl: '' } );
											},
										},
										__( 'Quitar poster', 'mwm-blocks' )
								  )
								: null,
							imageUrl
								? el(
										'div',
										{ className: 'mwm-muestra-editor__poster-preview' },
										el( 'img', {
											src: imageUrl,
											alt:
												item && item.imageAlt
													? item.imageAlt
													: item && item.title
													? item.title
													: '',
										} )
								  )
								: null,
							el(
								'p',
								{ style: { margin: '12px 0 8px', fontSize: '12px', fontWeight: 600 } },
								__( 'Video', 'mwm-blocks' )
							),
							el(
								MediaUploadCheck,
								null,
								el( MediaUpload, {
									onSelect: function ( media ) {
										updateItem( index, {
											videoFileId: media && media.id ? media.id : 0,
											videoFileUrl: media && media.url ? media.url : '',
											videoUrl: '',
										} );
									},
									allowedTypes: [ 'video' ],
									value: item && item.videoFileId ? item.videoFileId : 0,
									render: function ( mediaProps ) {
										return el(
											Button,
											{
												onClick: mediaProps.open,
												variant: 'secondary',
											},
											item && item.videoFileId
												? __( 'Reemplazar video', 'mwm-blocks' )
												: __( 'Subir video', 'mwm-blocks' )
										);
									},
								} )
							),
							item && item.videoFileId
								? el(
										Button,
										{
											variant: 'link',
											isDestructive: true,
											onClick: function () {
												updateItem( index, { videoFileId: 0, videoFileUrl: '', time: '' } );
											},
										},
										__( 'Quitar video subido', 'mwm-blocks' )
								  )
								: null,
							el( TextControl, {
								label: __( 'O enlace YouTube / Vimeo', 'mwm-blocks' ),
								value: item && item.videoUrl ? item.videoUrl : '',
								disabled: Boolean( item && item.videoFileId ),
								help: getVideoHelpText( item ),
								onChange: function ( value ) {
									var currentItem = items[ index ] || {};
									var parsed = parseVideoUrl( value );
									var patch = {
										videoUrl: value,
										videoFileId: 0,
										videoFileUrl: '',
									};

									if ( ! currentItem.imageId ) {
										patch.imageUrl = '';
									}

									updateItem( index, patch );

									if ( ! parsed ) {
										return;
									}

									var metaKey = parsed.provider + ':' + parsed.id;
									delete fetchedMetaRef.current[ metaKey ];
									fetchAndSetPoster(
										index,
										value,
										Object.assign( {}, currentItem, patch ),
										updateItem
									);
								},
								onBlur: function () {
									var currentItem = items[ index ] || {};
									var url = currentItem.videoUrl ? currentItem.videoUrl.trim() : '';

									if ( ! parseVideoUrl( url ) || currentItem.imageId ) {
										return;
									}

									var parsed = parseVideoUrl( url );
									var metaKey = parsed.provider + ':' + parsed.id;
									delete fetchedMetaRef.current[ metaKey ];
									fetchAndSetPoster( index, url, currentItem, updateItem );
								},
							} ),
							! getSlideVideoSource( item )
								? el(
										'p',
										{ style: { margin: '8px 0 0', fontSize: '12px', color: '#666' } },
										__( 'Este slide no se mostrara hasta que anadas un video.', 'mwm-blocks' )
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
											imageId: 0,
											imageAlt: '',
											imageUrl: '',
											videoFileId: 0,
											videoFileUrl: '',
											videoUrl: '',
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
							var videoSource = getSlideVideoSource( item );

							if ( ! videoSource ) {
								return null;
							}

							var playerLabel = getPlayAriaLabel( item && item.title ? item.title : '' );
							var playerContent = [
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
								),
							];

							return el(
								'div',
								{ key: index, className: 'swiper-slide' },
								el(
									'div',
									{ className: 'mwm-muestra__slide' },
									el(
										'div',
										{
											className: 'mwm-muestra__player mwm-muestra__player--editor-preview',
											'aria-label': playerLabel,
										},
										playerContent
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
