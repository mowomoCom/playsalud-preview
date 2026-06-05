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
	<nav class="mwm-header__nav" id="mwm-nav">
		<div class="mwm-container mwm-header__inner">
			<?php if ( has_custom_logo() ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mwm-header__logo" rel="home" aria-label="<?php esc_attr_e( 'PlaySalud', 'mwm-playsalud' ); ?>">
					<?php
					$custom_logo_id = get_theme_mod( 'custom_logo' );
					echo wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'custom-logo' ) );
					?>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mwm-header__logo" aria-label="<?php esc_attr_e( 'PlaySalud', 'mwm-playsalud' ); ?>">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="<?php esc_attr_e( 'PlaySalud', 'mwm-playsalud' ); ?>">
				</a>
			<?php endif; ?>
			<?php
			$header_button_title = trim( (string) get_theme_mod( 'mwm_playsalud_header_button_title', '' ) );
			$header_button_link  = trim( (string) get_theme_mod( 'mwm_playsalud_header_button_link', '' ) );

			if ( '' === $header_button_title ) {
				$header_button_title = __( 'Solicita info', 'mwm-playsalud' );
			}

			if ( '' === $header_button_link ) {
				$header_button_link = '#contacto';
			}
			?>
			<div class="mwm-header__menu-container" id="mwm-header-menu-container">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_id'        => 'mwm-header-menu',
						'menu_class'     => 'mwm-header__menu nav-links',
						'fallback_cb'    => 'mwm_playsalud_header_fallback_menu',
					)
				);
				?>
				<a href="<?php echo esc_url( $header_button_link ); ?>" class="mwm-btn mwm-btn--primary mwm-btn--sm mwm-header__cta mwm-header__cta--mobile"><?php echo esc_html( $header_button_title ); ?></a>
			</div>
			<a href="<?php echo esc_url( $header_button_link ); ?>" class="mwm-btn mwm-btn--primary mwm-btn--sm mwm-header__cta"><?php echo esc_html( $header_button_title ); ?></a>
			<button class="mwm-header__toggle nav-burger" type="button" aria-controls="mwm-header-menu-container" aria-expanded="false" aria-label="<?php esc_attr_e( 'Abrir menu', 'mwm-playsalud' ); ?>">
				<svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true" focusable="false">
					<path d="M3 6h14M3 10h14M3 14h14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
				</svg>
			</button>
		</div>
	</nav>
</header>
