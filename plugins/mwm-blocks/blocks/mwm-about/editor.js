( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment, useEffect } = wp.element;
	const { PanelBody, TextControl, TextareaControl, SelectControl, Button } = wp.components;
	const { InspectorControls, useBlockProps } = wp.blockEditor;

	const TITLE_STYLE_OPTIONS = [
		{ label: __( 'Ligera', 'mwm-blocks' ), value: 't-thin' },
		{ label: __( 'Negrita', 'mwm-blocks' ), value: 't-bold' },
		{ label: __( 'Negrita + Accent', 'mwm-blocks' ), value: 't-bold accent' },
	];

	function getDefaultTitleLines() {
		return [
			{ text: 'Un equipo serio,', className: 't-thin' },
			{ text: 'con un', className: 't-bold' },
		];
	}

	function normalizeTitleLines( titleLines, legacyTitle ) {
		const defaults = getDefaultTitleLines();
		let lines = Array.isArray( titleLines ) ? titleLines : [];

		if ( ! lines.length && legacyTitle ) {
			lines = [ { text: legacyTitle.trim(), className: 't-thin' } ];
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

	function getDefaultPillars() {
		return [
			{ label: 'RIGOR', claim: 'Asesor clinico por video.' },
			{ label: 'TONO', claim: 'Ni frivolo, ni solemne.' },
			{ label: 'ALCANCE', claim: 'B2B institucional.' },
			{ label: 'MODELO', claim: 'Produccion con redaccion propia.' },
		];
	}

	function normalizePillars( pillars ) {
		const defaults = getDefaultPillars();
		const list = Array.isArray( pillars ) ? pillars : [];

		if ( ! list.length ) {
			return defaults;
		}

		return list.slice( 0, 8 ).map( function ( item, index ) {
			const fallback = defaults[ index ] || { label: '', claim: '' };
			return {
				label: item && item.label ? item.label : fallback.label,
				claim: item && item.claim ? item.claim : fallback.claim,
			};
		} );
	}

	function renderAboutEdit( props ) {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'mwm-about mwm-about-editor' } );
		const defaultLines = getDefaultTitleLines();
		const isDefaultLines =
			JSON.stringify( attributes.titleLines || [] ) === JSON.stringify( defaultLines );
		const hasLegacyCustomTitle =
			!! attributes.title && attributes.title !== 'Un equipo serio con un lenguaje propio.';
		const sourceTitleLines = hasLegacyCustomTitle && isDefaultLines ? [] : attributes.titleLines;
		const normalizedLines = normalizeTitleLines( sourceTitleLines, attributes.title );
		const requestedCount = parseInt( attributes.titleLineCount, 10 );
		const titleLineCount = Number.isFinite( requestedCount )
			? Math.min( 5, Math.max( 1, requestedCount ) )
			: normalizedLines.length;
		const visibleTitleLines = normalizedLines.slice( 0, titleLineCount );
		const pillars = normalizePillars( attributes.pillars );
		const useLegacyQuotePreview =
			!! attributes.quote &&
			attributes.quote !== 'Hacemos videos que un profesional' &&
			attributes.quoteEmphasis === 'firmaria sin incomodidad' &&
			attributes.quoteSuffix === ', y que un paciente entiende sin sentirse infantilizado.';

		useEffect(
			function () {
				const shouldUpdateLines =
					JSON.stringify( normalizedLines ) !== JSON.stringify( attributes.titleLines || [] );
				const shouldUpdateCount = attributes.titleLineCount !== titleLineCount;
				const shouldUpdatePillars =
					JSON.stringify( pillars ) !== JSON.stringify( attributes.pillars || [] );

				if ( shouldUpdateLines || shouldUpdateCount || shouldUpdatePillars ) {
					setAttributes( {
						titleLines: normalizedLines,
						titleLineCount: titleLineCount,
						pillars: pillars,
					} );
				}
			},
			[
				attributes.titleLines,
				attributes.titleLineCount,
				attributes.title,
				attributes.pillars,
				titleLineCount,
			]
		);

		function updateTitleLine( index, patch ) {
			const next = normalizedLines.slice( 0, titleLineCount );
			next[ index ] = Object.assign( {}, next[ index ] || {}, patch );
			setAttributes( { titleLines: next } );
		}

		function updatePillar( index, patch ) {
			const next = pillars.slice();
			next[ index ] = Object.assign( {}, next[ index ] || {}, patch );
			setAttributes( { pillars: next } );
		}

		function removePillar( index ) {
			setAttributes( {
				pillars: pillars.filter( function ( _, idx ) {
					return idx !== index;
				} ),
			} );
		}

		const titlePreviewChildren = [];
		visibleTitleLines.forEach( function ( line, index ) {
			titlePreviewChildren.push(
				el(
					'span',
					{
						key: 'about-title-line-' + index,
						className: line.className || '',
					},
					line.text || ''
				)
			);

			if ( index === visibleTitleLines.length - 1 && attributes.titleAccent ) {
				titlePreviewChildren.push(
					el(
						'span',
						{ key: 'about-title-accent', className: 'accent' },
						' ' + attributes.titleAccent
					)
				);
			}

			if ( index < visibleTitleLines.length - 1 ) {
				titlePreviewChildren.push( el( 'br', { key: 'about-title-break-' + index } ) );
			}
		} );

		return el(
			Fragment,
			null,
			el(
				InspectorControls,
				null,
				el(
					PanelBody,
					{ title: __( 'Contenido principal', 'mwm-blocks' ), initialOpen: true },
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
							{ key: 'about-line-fields-' + index },
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
					el( TextControl, {
						label: __( 'Palabra o frase en accent', 'mwm-blocks' ),
						value: attributes.titleAccent || '',
						onChange: function ( value ) {
							setAttributes( { titleAccent: value } );
						},
					} ),
					el( TextareaControl, {
						label: __( 'Parrafo 1', 'mwm-blocks' ),
						value: attributes.lead || '',
						onChange: function ( value ) {
							setAttributes( { lead: value } );
						},
					} ),
					el( TextareaControl, {
						label: __( 'Parrafo 2', 'mwm-blocks' ),
						value: attributes.leadSecondary || '',
						onChange: function ( value ) {
							setAttributes( { leadSecondary: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Manifiesto', 'mwm-blocks' ), initialOpen: false },
					el( TextControl, {
						label: __( 'Tag visual', 'mwm-blocks' ),
						value: attributes.visualTag || '',
						onChange: function ( value ) {
							setAttributes( { visualTag: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Cita - antes', 'mwm-blocks' ),
						value: attributes.quote || '',
						onChange: function ( value ) {
							setAttributes( { quote: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Cita - enfasis', 'mwm-blocks' ),
						value: attributes.quoteEmphasis || '',
						onChange: function ( value ) {
							setAttributes( { quoteEmphasis: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Cita - despues', 'mwm-blocks' ),
						value: attributes.quoteSuffix || '',
						onChange: function ( value ) {
							setAttributes( { quoteSuffix: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Firma - nombre', 'mwm-blocks' ),
						value: attributes.signatureName || '',
						onChange: function ( value ) {
							setAttributes( { signatureName: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Firma - rol', 'mwm-blocks' ),
						value: attributes.signatureRole || '',
						onChange: function ( value ) {
							setAttributes( { signatureRole: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Sello', 'mwm-blocks' ),
						value: attributes.stamp || '',
						onChange: function ( value ) {
							setAttributes( { stamp: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Pilares', 'mwm-blocks' ), initialOpen: false },
					pillars.map( function ( pillar, index ) {
						return el(
							'div',
							{
								key: 'pillar-' + index,
								style: {
									marginBottom: '16px',
									paddingBottom: '12px',
									borderBottom: '1px solid #ddd',
								},
							},
							el( TextControl, {
								label: __( 'Label', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: pillar && pillar.label ? pillar.label : '',
								onChange: function ( value ) {
									updatePillar( index, { label: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Claim', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: pillar && pillar.claim ? pillar.claim : '',
								onChange: function ( value ) {
									updatePillar( index, { claim: value } );
								},
							} ),
							el(
								Button,
								{
									variant: 'link',
									isDestructive: true,
									onClick: function () {
										removePillar( index );
									},
								},
								__( 'Eliminar pilar', 'mwm-blocks' )
							)
						);
					} ),
					el(
						Button,
						{
							variant: 'secondary',
							onClick: function () {
								setAttributes( {
									pillars: pillars.concat( [ { label: '', claim: '' } ] ),
								} );
							},
						},
						__( 'Agregar pilar', 'mwm-blocks' )
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
						{ className: 'about-grid' },
						el(
							'div',
							{ className: 'reveal' },
							el( 'p', { className: 'eyebrow' }, attributes.eyebrow || '' ),
							el( 'h2', { className: 'about-title' }, titlePreviewChildren ),
							el( 'p', { className: 'about-p' }, attributes.lead || '' ),
							el( 'p', { className: 'about-p' }, attributes.leadSecondary || '' )
						),
						el(
							'div',
							{ className: 'about-visual reveal' },
							el( 'span', { className: 'about-visual-tag' }, attributes.visualTag || '' ),
							el(
								'svg',
								{
									className: 'about-quote-mark',
									viewBox: '0 0 24 24',
									fill: 'currentColor',
									'aria-hidden': 'true',
								},
								el( 'path', {
									d: 'M9 7H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2v2a2 2 0 0 1-2 2H4v2h1a4 4 0 0 0 4-4V7zm12 0h-4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h2v2a2 2 0 0 1-2 2h-1v2h1a4 4 0 0 0 4-4V7z',
								} )
							),
							el(
								'p',
								{ className: 'about-quote' },
								attributes.quote || '',
								! useLegacyQuotePreview && attributes.quoteEmphasis
									? el( 'em', null, attributes.quoteEmphasis )
									: null,
								! useLegacyQuotePreview ? attributes.quoteSuffix || '' : ''
							),
							el(
								'div',
								{ className: 'about-signature' },
								el(
									'div',
									{
										className: 'about-signature-mark',
										'aria-hidden': 'true',
									},
									el(
										'svg',
										{
											width: '18',
											height: '18',
											viewBox: '0 0 20 20',
											fill: 'none',
										},
										el( 'path', {
											d: 'M7 5.5l8 4.5-8 4.5V5.5z',
											fill: '#ffffff',
										} )
									)
								),
								el(
									'div',
									null,
									el(
										'div',
										{ className: 'about-signature-name' },
										attributes.signatureName || ''
									),
									el(
										'div',
										{ className: 'about-signature-role' },
										attributes.signatureRole || ''
									)
								)
							),
							el( 'span', { className: 'about-stamp' }, attributes.stamp || '' )
						)
					),
					el(
						'div',
						{ className: 'pillars reveal' },
						pillars.map( function ( pillar, index ) {
							return el(
								'div',
								{ key: 'preview-pillar-' + index, className: 'pillar' },
								el( 'div', { className: 'pillar-label' }, pillar && pillar.label ? pillar.label : '' ),
								el( 'div', { className: 'pillar-claim' }, pillar && pillar.claim ? pillar.claim : '' )
							);
						} )
					)
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/about-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/about' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderAboutEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
