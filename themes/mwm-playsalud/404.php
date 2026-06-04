<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$error_code = trim( (string) get_theme_mod( 'mwm_playsalud_404_code', '' ) );
if ( '' === $error_code ) {
	$error_code = '404';
}

$error_title = trim( (string) get_theme_mod( 'mwm_playsalud_404_title', '' ) );
if ( '' === $error_title ) {
	$error_title = __( 'Ups, pagina no encontrada', 'mwm-playsalud' );
}

$error_description = trim( (string) get_theme_mod( 'mwm_playsalud_404_description', '' ) );
if ( '' === $error_description ) {
	$error_description = __( 'Lo sentimos, la direccion que buscas no existe o ha sido movida. Verifica la URL o regresa al inicio para seguir comprando.', 'mwm-playsalud' );
}

$home_button_text = trim( (string) get_theme_mod( 'mwm_playsalud_404_home_button_text', '' ) );
if ( '' === $home_button_text ) {
	$home_button_text = __( 'Ir al inicio', 'mwm-playsalud' );
}

$home_button_url = trim( (string) get_theme_mod( 'mwm_playsalud_404_home_button_url', '' ) );
if ( '' === $home_button_url ) {
	$home_button_url = home_url( '/' );
}

$support_button_text = trim( (string) get_theme_mod( 'mwm_playsalud_404_support_button_text', '' ) );
if ( '' === $support_button_text ) {
	$support_button_text = __( 'Contactar Soporte', 'mwm-playsalud' );
}

$support_button_url = trim( (string) get_theme_mod( 'mwm_playsalud_404_support_button_url', '' ) );
if ( '' === $support_button_url ) {
	$support_button_url = '#contacto';
}
?>
<main class="mwm-main mwm-main--404">
	<section class="mwm-404">
		<div class="mwm-container">
			<div class="mwm-404__content">
				<h1 class="mwm-404__code"><?php echo esc_html( $error_code ); ?></h1>
				<h2 class="mwm-404__title"><?php echo esc_html( $error_title ); ?></h2>
				<p class="mwm-404__description"><?php echo esc_html( $error_description ); ?></p>
				<div class="mwm-404__actions">
					<a class="mwm-btn mwm-btn--sky mwm-btn--md" href="<?php echo esc_url( $home_button_url ); ?>">
						<svg class="mwm-404__icon" aria-hidden="true" viewBox="0 0 24 24" focusable="false">
							<path d="M12 3L4 10h2v10h5v-6h2v6h5V10h2l-8-7z"></path>
						</svg>
						<?php echo esc_html( $home_button_text ); ?>
					</a>
					<a class="mwm-btn mwm-btn--ghost mwm-btn--md" href="<?php echo esc_url( $support_button_url ); ?>">
						<svg class="mwm-404__icon" aria-hidden="true" viewBox="0 0 24 24" focusable="false">
							<path d="M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zm0 2v.2l8 5.2 8-5.2V7H4zm16 10V9.5l-7.5 4.9a1 1 0 0 1-1 0L4 9.5V17h16z"></path>
						</svg>
						<?php echo esc_html( $support_button_text ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
