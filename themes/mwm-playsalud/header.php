<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="mwm-header" id="mwm-header">
	<nav class="mwm-header__nav">
		<div class="mwm-container mwm-header__inner">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mwm-header__logo" aria-label="<?php esc_attr_e( 'PlaySalud', 'mwm-playsalud' ); ?>">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="<?php esc_attr_e( 'PlaySalud', 'mwm-playsalud' ); ?>">
			</a>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'mwm-header__menu',
					'fallback_cb'    => 'mwm_playsalud_header_fallback_menu',
				)
			);
			?>
			<a href="#contacto" class="mwm-btn mwm-btn--primary mwm-btn--sm"><?php esc_html_e( 'Solicita info', 'mwm-playsalud' ); ?></a>
		</div>
	</nav>
</header>
