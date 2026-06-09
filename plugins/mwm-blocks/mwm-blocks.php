<?php
/**
 * Plugin Name: MWM Blocks
 * Description: Bloques dinamicos MWM para secciones de PlaySalud.
 * Version: 1.0.0
 * Author: MWM
 * Text Domain: mwm-blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MWM_BLOCKS_PATH', plugin_dir_path( __FILE__ ) );
define( 'MWM_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

require_once MWM_BLOCKS_PATH . 'includes/render-mwm.php';

function mwm_blocks_register_categories( $categories ) {
	$new_categories = array(
		array(
			'slug'  => 'home',
			'title' => __( 'Home', 'mwm-blocks' ),
		),
		array(
			'slug'  => 'contacto',
			'title' => __( 'Contacto', 'mwm-blocks' ),
		),
	);

	return array_merge( $new_categories, $categories );
}
add_filter( 'block_categories_all', 'mwm_blocks_register_categories' );

function mwm_blocks_register_all() {
	$blocks_path = MWM_BLOCKS_PATH . 'blocks/';
	$entries     = glob( $blocks_path . '*', GLOB_ONLYDIR );

	if ( empty( $entries ) ) {
		return;
	}

	foreach ( $entries as $entry ) {
		$block_json = trailingslashit( $entry ) . 'block.json';
		if ( file_exists( $block_json ) ) {
			$registered_block = register_block_type( $entry );
			mwm_blocks_ensure_theme_global_style_dependency( $registered_block );
		}
	}
}
add_action( 'init', 'mwm_blocks_register_fancybox_assets', 9 );
add_action( 'init', 'mwm_blocks_register_all' );
add_action( 'rest_api_init', 'mwm_blocks_register_rest_routes' );

/**
 * Hace que los estilos de bloques MWM se impriman despues del global del theme PlaySalud.
 */
function mwm_blocks_ensure_theme_global_style_dependency( $registered_block ) {
	if ( ! ( $registered_block instanceof WP_Block_Type ) ) {
		return;
	}

	// En el admin (editor), esta dependencia puede impedir que Gutenberg imprima estilos del bloque.
	if ( is_admin() ) {
		return;
	}

	if ( ! mwm_blocks_is_playsalud_theme_active() ) {
		return;
	}

	if ( ! function_exists( 'wp_styles' ) ) {
		return;
	}

	$styles  = wp_styles();
	$handles = array();

	if ( ! empty( $registered_block->style_handles ) && is_array( $registered_block->style_handles ) ) {
		$handles = array_merge( $handles, $registered_block->style_handles );
	}

	if ( ! empty( $registered_block->view_style_handles ) && is_array( $registered_block->view_style_handles ) ) {
		$handles = array_merge( $handles, $registered_block->view_style_handles );
	}

	$handles = array_unique( array_filter( $handles ) );

	foreach ( $handles as $handle ) {
		if ( ! isset( $styles->registered[ $handle ] ) ) {
			continue;
		}

		$deps = $styles->registered[ $handle ]->deps;
		if ( ! is_array( $deps ) ) {
			$deps = array();
		}

		if ( in_array( 'mwm-playsalud-global', $deps, true ) ) {
			continue;
		}

		$deps[]                           = 'mwm-playsalud-global';
		$styles->registered[ $handle ]->deps = $deps;
	}
}

/**
 * Limita esta compatibilidad al theme de PlaySalud para no afectar otros themes.
 */
function mwm_blocks_is_playsalud_theme_active() {
	$theme = wp_get_theme();

	return 'mwm-playsalud' === $theme->get_stylesheet() || 'mwm-playsalud' === $theme->get_template();
}

/**
 * Fuerza auto registro en cliente para bloques dinamicos MWM.
 *
 * En WP 7+, esto permite que bloques registrados solo en PHP
 * aparezcan en el inserter sin requerir registro JS manual.
 */
function mwm_blocks_enable_auto_register( $args, $block_type ) {
	if ( ! is_string( $block_type ) || 0 !== strpos( $block_type, 'mwm/' ) ) {
		return $args;
	}

	$supports = array();
	if ( isset( $args['supports'] ) && is_array( $args['supports'] ) ) {
		$supports = $args['supports'];
	}

	$supports['auto_register'] = true;
	$supports['autoRegister']  = true;
	$args['supports']          = $supports;

	return $args;
}
add_filter( 'register_block_type_args', 'mwm_blocks_enable_auto_register', 10, 2 );

function mwm_blocks_enqueue_editor_script() {
	$editor_script_path = MWM_BLOCKS_PATH . 'assets/js/editor.js';

	wp_enqueue_script(
		'mwm-blocks-editor',
		MWM_BLOCKS_URL . 'assets/js/editor.js',
		array( 'wp-dom-ready' ),
		file_exists( $editor_script_path ) ? filemtime( $editor_script_path ) : null,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'mwm_blocks_enqueue_editor_script' );

function mwm_blocks_enqueue_editor_base_style() {
	if ( ! is_admin() ) {
		return;
	}

	$editor_style_path = MWM_BLOCKS_PATH . 'assets/css/editor-base.css';
	if ( ! file_exists( $editor_style_path ) ) {
		return;
	}

	wp_enqueue_style(
		'mwm-blocks-editor-base',
		MWM_BLOCKS_URL . 'assets/css/editor-base.css',
		array(),
		filemtime( $editor_style_path )
	);
}
add_action( 'enqueue_block_assets', 'mwm_blocks_enqueue_editor_base_style' );

function mwm_blocks_register_fancybox_assets() {
	$vendor_path = MWM_BLOCKS_PATH . 'assets/vendor/fancybox/';
	$vendor_url  = MWM_BLOCKS_URL . 'assets/vendor/fancybox/';
	$version     = '5.0.36';

	wp_register_style(
		'fancyapps-fancybox',
		$vendor_url . 'fancybox.css',
		array(),
		file_exists( $vendor_path . 'fancybox.css' ) ? $version : null
	);

	wp_register_script(
		'fancyapps-fancybox',
		$vendor_url . 'fancybox.umd.js',
		array(),
		file_exists( $vendor_path . 'fancybox.umd.js' ) ? $version : null,
		true
	);
}
/**
 * Encola estilos de Fancybox cuando el bloque muestra esta en la pagina.
 *
 * @param string $block_content HTML del bloque.
 * @param array  $block         Datos del bloque.
 * @return string
 */
function mwm_blocks_enqueue_muestra_fancybox_styles( $block_content, $block ) {
	if ( is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
		return $block_content;
	}

	if ( empty( $block['blockName'] ) || 'mwm/muestra' !== $block['blockName'] ) {
		return $block_content;
	}

	wp_enqueue_style( 'fancyapps-fancybox' );

	return $block_content;
}

/**
 * REST: metadatos de video embebido para el editor (poster).
 */
function mwm_blocks_register_rest_routes() {
	register_rest_route(
		'mwm-blocks/v1',
		'/video-duration',
		array(
			'methods'             => 'GET',
			'permission_callback' => function () {
				return current_user_can( 'edit_posts' );
			},
			'args'                => array(
				'url' => array(
					'required'          => true,
					'type'              => 'string',
					'sanitize_callback' => function ( $value ) {
						return is_string( $value ) ? trim( $value ) : '';
					},
				),
			),
			'callback'            => function ( $request ) {
				$url = (string) $request->get_param( 'url' );

				if ( '' === trim( $url ) ) {
					return new WP_Error(
						'mwm_missing_params',
						__( 'Indica la URL del video.', 'mwm-blocks' ),
						array( 'status' => 400 )
					);
				}

				$video = mwm_blocks_parse_video_url( $url );

				if ( ! is_array( $video ) ) {
					return new WP_Error(
						'mwm_invalid_video_url',
						__( 'URL de video no valida.', 'mwm-blocks' ),
						array( 'status' => 400 )
					);
				}

				$metadata = mwm_blocks_fetch_embed_metadata( $video );

				return array(
					'thumbnail' => isset( $metadata['thumbnail'] ) ? (string) $metadata['thumbnail'] : '',
				);
			},
		)
	);
}
add_filter( 'render_block', 'mwm_blocks_enqueue_muestra_fancybox_styles', 10, 2 );

function mwm_blocks_enqueue_frontend_script() {
	wp_enqueue_script(
		'mwm-blocks-frontend',
		MWM_BLOCKS_URL . 'assets/js/frontend.js',
		array(),
		filemtime( MWM_BLOCKS_PATH . 'assets/js/frontend.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'mwm_blocks_enqueue_frontend_script' );
