( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment, useEffect } = wp.element;
	const { Button, PanelBody, TextControl, TextareaControl, SelectControl } = wp.components;
	const {
		InspectorControls,
		MediaUpload,
		MediaUploadCheck,
		useBlockProps,
	} = wp.blockEditor;

	const TITLE_STYLE_OPTIONS = [
		{ label: __( 'Ligera', 'mwm-blocks' ), value: 't-thin' },
		{ label: __( 'Negrita', 'mwm-blocks' ), value: 't-bold' },
		{ label: __( 'Negrita + Accent', 'mwm-blocks' ), value: 't-bold accent' },
		{ label: __( 'Ligera + Accent', 'mwm-blocks' ), value: 't-thin accent' },
	];

	function getDefaultTitleLines() {
		return [
			{ text: 'Educacion sanitaria', className: 't-thin' },
			{ text: 'basada en evidencia,', className: 't-bold accent' },
			{
				text: 'para pacientes, profesionales e instituciones.',
				className: 't-thin',
			},
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

	function renderBannerEdit( props ) {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'mwm-banner-editor' } );
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
					el( SelectControl, {
						label: __( 'Cantidad de lineas en titulo', 'mwm-blocks' ),
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
						el(
							'h2',
							{ className: 'mwm-banner__title' },
							visibleTitleLines.map( function ( line, index ) {
								return el(
									Fragment,
									{ key: 'preview-line-' + index },
									el( 'span', { className: line.className || 't-thin' }, line.text || '' ),
									index < visibleTitleLines.length - 1 ? el( 'br' ) : null
								);
							} )
						),
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
