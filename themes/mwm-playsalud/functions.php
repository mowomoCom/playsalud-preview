<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MWM_PLAYSALUD_THEME_VERSION', '1.0.0' );

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

function mwm_playsalud_enqueue_assets() {
	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();

	wp_enqueue_style( 'mwm-playsalud-theme', get_stylesheet_uri(), array(), MWM_PLAYSALUD_THEME_VERSION );
	wp_enqueue_style( 'mwm-playsalud-fonts', $theme_uri . '/assets/fonts/fonts.css', array(), filemtime( $theme_path . '/assets/fonts/fonts.css' ) );
	wp_enqueue_style( 'mwm-playsalud-global', $theme_uri . '/assets/css/global.css', array(), filemtime( $theme_path . '/assets/css/global.css' ) );
	wp_enqueue_style( 'mwm-playsalud-header', $theme_uri . '/assets/css/header.css', array( 'mwm-playsalud-global' ), filemtime( $theme_path . '/assets/css/header.css' ) );
	wp_enqueue_style( 'mwm-playsalud-footer', $theme_uri . '/assets/css/footer.css', array( 'mwm-playsalud-global' ), filemtime( $theme_path . '/assets/css/footer.css' ) );
	wp_enqueue_style( 'mwm-playsalud-forms', $theme_uri . '/assets/css/forms.css', array( 'mwm-playsalud-global' ), filemtime( $theme_path . '/assets/css/forms.css' ) );

	wp_enqueue_script( 'mwm-playsalud-theme', $theme_uri . '/assets/js/theme.js', array(), filemtime( $theme_path . '/assets/js/theme.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'mwm_playsalud_enqueue_assets', 5 );

function mwm_playsalud_enqueue_editor_assets() {
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
add_action( 'enqueue_block_editor_assets', 'mwm_playsalud_enqueue_editor_assets' );

function mwm_playsalud_header_fallback_menu() {
	echo '<ul class="mwm-header__menu">';
	echo '<li><a href="#verticales">PlayCare</a></li>';
	echo '<li><a href="#curso">PlayAcademy</a></li>';
	echo '<li><a href="#about">Sobre</a></li>';
	echo '<li><a href="#instituciones">Para instituciones</a></li>';
	echo '<li><a href="#contacto">Contacto</a></li>';
	echo '</ul>';
}
