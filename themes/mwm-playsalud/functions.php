<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MWM_PLAYSALUD_THEME_VERSION', '1.0.0' );

$mwm_playsalud_customizer_file = get_template_directory() . '/include/customizer.php';

if ( file_exists( $mwm_playsalud_customizer_file ) ) {
	require_once $mwm_playsalud_customizer_file;
}

function mwm_playsalud_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 220,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style(
		array(
			'assets/fonts/fonts.css',
			'assets/css/global.css',
			'assets/css/editor.css',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'mwm-playsalud' ),
			'footer'  => __( 'Menu footer', 'mwm-playsalud' ),
		)
	);
}
add_action( 'after_setup_theme', 'mwm_playsalud_setup' );

/**
 * Usa index.php?rest_route= cuando Apache no reescribe /wp-json/.
 *
 * Sin esto, Gutenberg recibe HTML 404 en lugar de JSON y falla el guardado.
 */
function mwm_playsalud_rest_url_index_fallback( $url, $path, $blog_id, $scheme ) {
	$route = '/' . ltrim( (string) $path, '/' );

	return home_url( '/index.php?rest_route=' . $route, $scheme );
}
add_filter( 'rest_url', 'mwm_playsalud_rest_url_index_fallback', 10, 4 );

function mwm_playsalud_enqueue_assets() {
	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();
	$style_404  = $theme_path . '/assets/css/404.css';
	$style_page_text = $theme_path . '/assets/css/page-text.css';

	wp_enqueue_style( 'mwm-playsalud-theme', get_stylesheet_uri(), array(), MWM_PLAYSALUD_THEME_VERSION );
	wp_enqueue_style( 'mwm-playsalud-fonts', $theme_uri . '/assets/fonts/fonts.css', array(), filemtime( $theme_path . '/assets/fonts/fonts.css' ) );
	wp_enqueue_style( 'mwm-playsalud-global', $theme_uri . '/assets/css/global.css', array(), filemtime( $theme_path . '/assets/css/global.css' ) );
	wp_enqueue_style( 'mwm-playsalud-header', $theme_uri . '/assets/css/header.css', array( 'mwm-playsalud-global' ), filemtime( $theme_path . '/assets/css/header.css' ) );
	wp_enqueue_style( 'mwm-playsalud-footer', $theme_uri . '/assets/css/footer.css', array( 'mwm-playsalud-global' ), filemtime( $theme_path . '/assets/css/footer.css' ) );
	wp_enqueue_style( 'mwm-playsalud-forms', $theme_uri . '/assets/css/forms.css', array( 'mwm-playsalud-global' ), filemtime( $theme_path . '/assets/css/forms.css' ) );

	if ( is_404() && file_exists( $style_404 ) ) {
		wp_enqueue_style( 'mwm-playsalud-404', $theme_uri . '/assets/css/404.css', array( 'mwm-playsalud-global' ), filemtime( $style_404 ) );
	}
	if ( is_page_template( 'page-text.php' ) && file_exists( $style_page_text ) ) {
		wp_enqueue_style( 'mwm-playsalud-page-text', $theme_uri . '/assets/css/page-text.css', array( 'mwm-playsalud-global' ), filemtime( $style_page_text ) );
	}

	wp_enqueue_style( 'mwm-playsalud-swiper', $theme_uri . '/assets/js/swiper/swiper-bundle.min.css', array(), filemtime( $theme_path . '/assets/js/swiper/swiper-bundle.min.css' ) );

	wp_enqueue_script( 'mwm-playsalud-swiper', $theme_uri . '/assets/js/swiper/swiper-bundle.min.js', array(), filemtime( $theme_path . '/assets/js/swiper/swiper-bundle.min.js' ), true );
	wp_enqueue_script( 'mwm-playsalud-scripts', $theme_uri . '/assets/js/scripts.js', array( 'jquery', 'mwm-playsalud-swiper' ), filemtime( $theme_path . '/assets/js/scripts.js' ), true );
	wp_enqueue_script( 'mwm-playsalud-theme', $theme_uri . '/assets/js/theme.js', array(), filemtime( $theme_path . '/assets/js/theme.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'mwm_playsalud_enqueue_assets', 5 );

function mwm_playsalud_enqueue_editor_assets() {
	if ( ! is_admin() ) {
		return;
	}

	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();
	$fonts_css  = $theme_path . '/assets/fonts/fonts.css';
	$global_css = $theme_path . '/assets/css/global.css';
	$editor_css = $theme_path . '/assets/css/editor.css';
	$fonts_ver  = file_exists( $fonts_css ) ? filemtime( $fonts_css ) : MWM_PLAYSALUD_THEME_VERSION;
	$global_ver = file_exists( $global_css ) ? filemtime( $global_css ) : MWM_PLAYSALUD_THEME_VERSION;
	$editor_ver = file_exists( $editor_css ) ? filemtime( $editor_css ) : MWM_PLAYSALUD_THEME_VERSION;

	wp_enqueue_style( 'mwm-playsalud-fonts', $theme_uri . '/assets/fonts/fonts.css', array(), $fonts_ver );
	wp_enqueue_style( 'mwm-playsalud-global', $theme_uri . '/assets/css/global.css', array( 'mwm-playsalud-fonts' ), $global_ver );
	wp_enqueue_style( 'mwm-playsalud-editor', $theme_uri . '/assets/css/editor.css', array( 'mwm-playsalud-global' ), $editor_ver );
}
add_action( 'enqueue_block_assets', 'mwm_playsalud_enqueue_editor_assets' );

function mwm_playsalud_enqueue_customizer_assets() {
	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();
	$script     = $theme_path . '/assets/js/customizer-social-repeater.js';
	$style      = $theme_path . '/assets/css/customizer-social-repeater.css';
	$script_ver = file_exists( $script ) ? filemtime( $script ) : MWM_PLAYSALUD_THEME_VERSION;
	$style_ver  = file_exists( $style ) ? filemtime( $style ) : MWM_PLAYSALUD_THEME_VERSION;

	wp_enqueue_media();
	wp_enqueue_script(
		'mwm-playsalud-customizer-social-repeater',
		$theme_uri . '/assets/js/customizer-social-repeater.js',
		array( 'jquery', 'customize-controls' ),
		$script_ver,
		true
	);
	wp_enqueue_style(
		'mwm-playsalud-customizer-social-repeater',
		$theme_uri . '/assets/css/customizer-social-repeater.css',
		array(),
		$style_ver
	);
}
add_action( 'customize_controls_enqueue_scripts', 'mwm_playsalud_enqueue_customizer_assets' );

function mwm_playsalud_header_fallback_menu() {
	echo '<ul class="mwm-header__menu">';
	echo '<li><a href="#verticales">PlayCare</a></li>';
	echo '<li><a href="#curso">PlayAcademy</a></li>';
	echo '<li><a href="#about">Sobre</a></li>';
	echo '<li><a href="#instituciones">Para instituciones</a></li>';
	echo '<li><a href="#contacto">Contacto</a></li>';
	echo '</ul>';
}
