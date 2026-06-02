( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, TextareaControl } = wp.components;
	const { InspectorControls, useBlockProps } = wp.blockEditor;

	function renderContactoEdit( props ) {
		const { attributes, setAttributes } = props;
		const blockProps = useBlockProps( { className: 'mwm-contacto-editor' } );

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
						label: __( 'Descripcion', 'mwm-blocks' ),
						value: attributes.lead || '',
						onChange: function ( value ) {
							setAttributes( { lead: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Formulario', 'mwm-blocks' ), initialOpen: true },
					el( TextControl, {
						label: __( 'Label nombre', 'mwm-blocks' ),
						value: attributes.nameLabel || '',
						onChange: function ( value ) {
							setAttributes( { nameLabel: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Label email', 'mwm-blocks' ),
						value: attributes.emailLabel || '',
						onChange: function ( value ) {
							setAttributes( { emailLabel: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Label mensaje', 'mwm-blocks' ),
						value: attributes.messageLabel || '',
						onChange: function ( value ) {
							setAttributes( { messageLabel: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Texto boton enviar', 'mwm-blocks' ),
						value: attributes.submitText || '',
						onChange: function ( value ) {
							setAttributes( { submitText: value } );
						},
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
						null,
						el( 'p', { className: 'mwm-eyebrow' }, attributes.eyebrow || '' ),
						el( 'h2', null, attributes.title || '' ),
						el( 'p', null, attributes.lead || '' )
					),
					el(
						'form',
						{ className: 'mwm-form-card mwm-contacto__form' },
						el(
							'div',
							{ className: 'mwm-form-row mwm-form-row--split' },
							el( 'label', { className: 'mwm-form-label' }, attributes.nameLabel || '', el( 'input', { className: 'mwm-form-input', type: 'text', disabled: true } ) ),
							el( 'label', { className: 'mwm-form-label' }, attributes.emailLabel || '', el( 'input', { className: 'mwm-form-input', type: 'email', disabled: true } ) )
						),
						el(
							'div',
							{ className: 'mwm-form-row' },
							el( 'label', { className: 'mwm-form-label' }, attributes.messageLabel || '', el( 'textarea', { className: 'mwm-form-textarea', disabled: true } ) )
						),
						el( 'button', { className: 'mwm-btn mwm-btn--primary mwm-btn--md', type: 'button' }, attributes.submitText || '' )
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
