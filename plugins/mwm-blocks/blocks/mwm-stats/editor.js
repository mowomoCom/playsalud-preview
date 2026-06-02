( function () {
	const { __ } = wp.i18n;
	const { addFilter } = wp.hooks;
	const { createElement: el, Fragment } = wp.element;
	const { PanelBody, TextControl, Button } = wp.components;
	const { InspectorControls, useBlockProps } = wp.blockEditor;

	function normalizeItems( items ) {
		return Array.isArray( items ) ? items : [];
	}

	function renderStatsEdit( props ) {
		const { attributes, setAttributes } = props;
		const items = normalizeItems( attributes.items );
		const blockProps = useBlockProps( { className: 'mwm-stats-editor' } );

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
					{ title: __( 'Contenido', 'mwm-blocks' ), initialOpen: true },
					el( TextControl, {
						label: __( 'Eyebrow', 'mwm-blocks' ),
						value: attributes.eyebrow || '',
						onChange: function ( value ) {
							setAttributes( { eyebrow: value } );
						},
					} )
				),
				el(
					PanelBody,
					{ title: __( 'Items', 'mwm-blocks' ), initialOpen: true },
					items.map( function ( item, index ) {
						return el(
							'div',
							{ key: index, style: { marginBottom: '16px', paddingBottom: '12px', borderBottom: '1px solid #ddd' } },
							el( TextControl, {
								label: __( 'Valor', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: item && item.value ? item.value : '',
								onChange: function ( value ) {
									updateItem( index, { value: value } );
								},
							} ),
							el( TextControl, {
								label: __( 'Etiqueta', 'mwm-blocks' ) + ' ' + ( index + 1 ),
								value: item && item.label ? item.label : '',
								onChange: function ( value ) {
									updateItem( index, { label: value } );
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
									items: items.concat( [ { value: '', label: '' } ] ),
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
					el(
						'div',
						{ className: 'mwm-stats__grid' },
						items.map( function ( item, index ) {
							return el(
								'article',
								{ key: index, className: 'mwm-stats__item' },
								el( 'strong', null, item && item.value ? item.value : '' ),
								el( 'span', null, item && item.label ? item.label : '' )
							);
						} )
					)
				)
			)
		);
	}

	addFilter(
		'blocks.registerBlockType',
		'mwm/stats-editor-settings',
		function ( settings, name ) {
			if ( name !== 'mwm/stats' ) {
				return settings;
			}

			return Object.assign( {}, settings, {
				edit: renderStatsEdit,
				save: function () {
					return null;
				},
			} );
		}
	);
} )();
