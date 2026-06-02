( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, Button } = wp.components;
	const { InspectorControls, useBlockProps } = wp.blockEditor;

	function normalizeItems( items ) {
		return Array.isArray( items ) ? items : [];
	}

	function renderInstitucionesEdit( props ) {
		const { attributes, setAttributes } = props;
		const items = normalizeItems( attributes.items );
		const blockProps = useBlockProps( { className: 'mwm-instituciones-editor' } );

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
						value: attributes.title || '',
						onChange: function ( value ) {
							setAttributes( { title: value } );
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
									items: items.concat( [ { title: '', description: '' } ] ),
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
					el( 'p', { className: 'mwm-eyebrow' }, attributes.eyebrow || '' ),
					el( 'h2', null, attributes.title || '' ),
					el(
						'div',
						{ className: 'mwm-instituciones__grid' },
						items.map( function ( item, index ) {
							return el(
								'article',
								{ key: index, className: 'mwm-instituciones__card' },
								el( 'h3', null, item && item.title ? item.title : '' ),
								el( 'p', null, item && item.description ? item.description : '' )
							);
						} )
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
