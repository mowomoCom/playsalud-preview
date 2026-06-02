<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main class="mwm-main mwm-main--404">
	<section class="mwm-section">
		<div class="mwm-container">
			<div class="mwm-not-found">
				<p class="mwm-eyebrow">404</p>
				<h1><?php esc_html_e( 'Pagina no encontrada', 'mwm-playsalud' ); ?></h1>
				<p><?php esc_html_e( 'La ruta solicitada no esta disponible o fue movida.', 'mwm-playsalud' ); ?></p>
				<a class="mwm-btn mwm-btn--primary mwm-btn--md" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Volver al inicio', 'mwm-playsalud' ); ?>
				</a>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
