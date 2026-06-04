( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, TextareaControl, SelectControl, Button } = wp.components;
	const { InspectorControls, useBlockProps } = wp.blockEditor;
	const LEGACY_DEFAULT_TITLE = 'Solicita informacion para tu institucion.';

	const DEFAULT_FEATURES = [
		{
			icon: 'sun',
			title: 'Respuesta en 48 h',
			text: 'Te contestamos dentro de los dos primeros dias laborables.',
		},
		{
			icon: 'message',
			title: 'Demo personalizada',
			text: 'Si encajamos, te mostramos como PlaySalud se adapta a tu centro.',
		},
		{
			icon: 'check',
			title: 'Sin compromiso',
			text: 'El primer contacto es gratuito. Sin venta agresiva.',
		},
	];

	const ICON_OPTIONS = [
		{ label: __( 'Sol', 'mwm-blocks' ), value: 'sun' },
		{ label: __( 'Mensaje', 'mwm-blocks' ), value: 'message' },
		{ label: __( 'Check', 'mwm-blocks' ), value: 'check' },
		{ label: __( 'Reloj', 'mwm-blocks' ), value: 'clock' },
		{ label: __( 'Usuario', 'mwm-blocks' ), value: 'user' },
	];

	const ICON_PREVIEW = {
		sun: '☀',
		message: '💬',
		check: '✓',
		clock: '◷',
		user: '👤',
	};

	function getFeatureItems( features ) {
		if ( ! Array.isArray( features ) || ! features.length ) {
			return DEFAULT_FEATURES;
		}

		return features.map( function ( item ) {
			return {
				icon: item && item.icon ? item.icon : 'sun',
				title: item && item.title ? item.title : '',
				text: item && item.text ? item.text : '',
			};
		} );
	}

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
				thin: 'Solicita informacion',
				bold: 'para tu',
				accent: 'institucion.',
			};
		}

		return { thin: legacyTitle, bold: '', accent: '' };
	}

	function renderContactoEdit( props ) {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'mwm-contacto-editor' } );
		const features = getFeatureItems( attributes.features );
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

		function updateFeature( index, key, value ) {
			const nextFeatures = features.map( function ( item, itemIndex ) {
				if ( itemIndex !== index ) {
					return item;
				}

				return Object.assign( {}, item, {
					[ key ]: value,
				} );
			} );

			setAttributes( { features: nextFeatures } );
		}

		function removeFeature( index ) {
			const nextFeatures = features.filter( function ( _item, itemIndex ) {
				return itemIndex !== index;
			} );

			setAttributes( {
				features: nextFeatures.length ? nextFeatures : [ DEFAULT_FEATURES[ 0 ] ],
			} );
		}

		function addFeature() {
			setAttributes( {
				features: features.concat( [ { icon: 'sun', title: '', text: '' } ] ),
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
						label: __( 'Descripcion', 'mwm-blocks' ),
						value: attributes.lead || '',
						onChange: function ( value ) {
							setAttributes( { lead: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Beneficios', 'mwm-blocks' ), initialOpen: false },
					features.map( function ( feature, index ) {
						return el(
							'div',
							{ className: 'mwm-contacto-editor__feature', key: 'feature-' + index },
							el( TextControl, {
								label: __( 'Titulo', 'mwm-blocks' ) + ' #' + ( index + 1 ),
								value: feature.title || '',
								onChange: function ( value ) {
									updateFeature( index, 'title', value );
								},
							} ),
							el( TextareaControl, {
								label: __( 'Texto', 'mwm-blocks' ),
								value: feature.text || '',
								onChange: function ( value ) {
									updateFeature( index, 'text', value );
								},
								rows: 3,
							} ),
							el( SelectControl, {
								label: __( 'Icono', 'mwm-blocks' ),
								value: feature.icon || 'sun',
								options: ICON_OPTIONS,
								onChange: function ( value ) {
									updateFeature( index, 'icon', value );
								},
							} ),
							el(
								Button,
								{
									variant: 'secondary',
									isDestructive: true,
									onClick: function () {
										removeFeature( index );
									},
								},
								__( 'Eliminar item', 'mwm-blocks' )
							)
						);
					} ),
					el(
						Button,
						{
							variant: 'primary',
							onClick: addFeature,
						},
						__( 'Agregar item', 'mwm-blocks' )
					)
				),
				el(
					PanelBody,
					{ title: __( 'Formulario', 'mwm-blocks' ), initialOpen: true },
					el( TextareaControl, {
						label: __( 'Shortcode de Contact Form 7', 'mwm-blocks' ),
						help: __( 'Pega aqui el shortcode completo, por ejemplo: [contact-form-7 id="123" title="Contacto"]', 'mwm-blocks' ),
						value: attributes.contactFormShortcode || '',
						onChange: function ( value ) {
							setAttributes( { contactFormShortcode: value } );
						},
						rows: 4,
					} )
				)
			),
			el(
				'section',
				blockProps,
				el(
					'div',
					{ className: 'mwm-container mwm-contacto__grid' },
					el(
						'div',
						{ className: 'mwm-contacto__side' },
						el( 'p', { className: 'mwm-eyebrow' }, attributes.eyebrow || '' ),
						el(
							'h2',
							{ className: 'section-title mwm-contacto__title' },
							titleParts.thin ? el( 'span', { className: 't-thin' }, titleParts.thin + ' ' ) : null,
							titleParts.bold ? el( 'span', { className: 't-bold' }, titleParts.bold ) : null,
							titleParts.accent ? el( 'span', { className: 'accent' }, ' ' + titleParts.accent ) : null,
							! titleParts.thin && ! titleParts.bold && ! titleParts.accent ? attributes.title || '' : null
						),
						el( 'p', { className: 'section-subtitle' }, attributes.lead || '' ),
						el(
							'div',
							{ className: 'contacto-features' },
							features.map( function ( feature, index ) {
								return el(
									'div',
									{ className: 'contacto-feature', key: 'preview-feature-' + index },
									el(
										'div',
										{ className: 'contacto-feature-icon' },
										el( 'span', null, ICON_PREVIEW[ feature.icon ] || '•' )
									),
									el(
										'div',
										null,
										el( 'div', { className: 'contacto-feature-title' }, feature.title || __( 'Titulo del beneficio', 'mwm-blocks' ) ),
										el( 'div', { className: 'contacto-feature-text' }, feature.text || __( 'Texto del beneficio.', 'mwm-blocks' ) )
									)
								);
							} )
						)
					),
					el(
						'div',
						{ className: 'mwm-form-card mwm-contacto__form mwm-contacto__form-preview' },
						el( 'p', { className: 'mwm-form-label' }, __( 'Formulario Contact Form 7', 'mwm-blocks' ) ),
						attributes.contactFormShortcode
							? el( 'code', { className: 'mwm-contacto__shortcode-preview' }, attributes.contactFormShortcode )
							: el(
								'p',
								{ className: 'mwm-contacto__shortcode-empty' },
								__( 'Pega un shortcode en el panel lateral para renderizar el formulario en el frontend.', 'mwm-blocks' )
							)
					)
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/contacto-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/contacto' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderContactoEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
