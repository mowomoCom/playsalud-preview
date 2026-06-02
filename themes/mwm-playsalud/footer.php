<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<footer class="mwm-footer">
	<div class="mwm-container">
		<div class="mwm-footer__grid">
			<div class="mwm-footer__brand">
				<?php if ( has_custom_logo() ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mwm-footer__logo" rel="home" aria-label="<?php esc_attr_e( 'PlaySalud', 'mwm-playsalud' ); ?>">
						<?php
						$custom_logo_id = get_theme_mod( 'custom_logo' );
						echo wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'custom-logo' ) );
						?>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mwm-footer__logo">
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="<?php esc_attr_e( 'PlaySalud', 'mwm-playsalud' ); ?>">
					</a>
				<?php endif; ?>
				<p class="mwm-footer__tagline"><?php esc_html_e( 'Salud en video. Formacion y comunicacion clinica con estandares editoriales.', 'mwm-playsalud' ); ?></p>
			</div>
			<div>
				<h3 class="mwm-footer__title"><?php esc_html_e( 'Navega', 'mwm-playsalud' ); ?></h3>
				<ul class="mwm-footer__links">
					<li><a href="#verticales">PlayCare</a></li>
					<li><a href="#curso">PlayAcademy</a></li>
					<li><a href="#about"><?php esc_html_e( 'Sobre PlaySalud', 'mwm-playsalud' ); ?></a></li>
					<li><a href="#instituciones"><?php esc_html_e( 'Para instituciones', 'mwm-playsalud' ); ?></a></li>
				</ul>
			</div>
			<div>
				<h3 class="mwm-footer__title"><?php esc_html_e( 'Contacto', 'mwm-playsalud' ); ?></h3>
				<ul class="mwm-footer__links">
					<li><a href="mailto:info@playsalud.com">info@playsalud.com</a></li>
					<li><a href="#contacto"><?php esc_html_e( 'Solicita una demo', 'mwm-playsalud' ); ?></a></li>
				</ul>
			</div>
			<div>
				<h3 class="mwm-footer__title"><?php esc_html_e( 'Legal', 'mwm-playsalud' ); ?></h3>
				<ul class="mwm-footer__links">
					<li><a href="#"><?php esc_html_e( 'Aviso legal', 'mwm-playsalud' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Privacidad', 'mwm-playsalud' ); ?></a></li>
					<li><a href="#"><?php esc_html_e( 'Cookies', 'mwm-playsalud' ); ?></a></li>
				</ul>
			</div>
		</div>
		<div class="mwm-footer__bottom">
			<span><?php echo esc_html( gmdate( 'Y' ) ); ?> PlaySalud. <?php esc_html_e( 'Todos los derechos reservados.', 'mwm-playsalud' ); ?></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
