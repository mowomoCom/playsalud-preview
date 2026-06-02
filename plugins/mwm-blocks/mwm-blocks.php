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
			register_block_type( $entry );
		}
	}
}
add_action( 'init', 'mwm_blocks_register_all' );

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
	wp_enqueue_script(
		'mwm-blocks-editor',
		MWM_BLOCKS_URL . 'assets/js/editor.js',
		array( 'wp-dom-ready' ),
		filemtime( MWM_BLOCKS_PATH . 'assets/js/editor.js' ),
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'mwm_blocks_enqueue_editor_script' );

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
