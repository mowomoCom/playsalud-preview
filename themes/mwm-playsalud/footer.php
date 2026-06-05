<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php
$footer_logo_id = absint( get_theme_mod( 'mwm_playsalud_footer_logo', 0 ) );

$footer_description = trim( (string) get_theme_mod( 'mwm_playsalud_footer_description', '' ) );
if ( '' === $footer_description ) {
	$footer_description = __( 'Salud en video. Formacion y comunicacion clinica con estandares editoriales.', 'mwm-playsalud' );
}

$footer_menu_title_1 = trim( (string) get_theme_mod( 'mwm_playsalud_footer_menu_title_1', '' ) );
if ( '' === $footer_menu_title_1 ) {
	$footer_menu_title_1 = __( 'Navega', 'mwm-playsalud' );
}

$footer_menu_title_2 = trim( (string) get_theme_mod( 'mwm_playsalud_footer_menu_title_2', '' ) );
if ( '' === $footer_menu_title_2 ) {
	$footer_menu_title_2 = __( 'Contacto', 'mwm-playsalud' );
}

$footer_menu_title_3 = trim( (string) get_theme_mod( 'mwm_playsalud_footer_menu_title_3', '' ) );
if ( '' === $footer_menu_title_3 ) {
	$footer_menu_title_3 = __( 'Legal', 'mwm-playsalud' );
}

$footer_email = sanitize_email( trim( (string) get_theme_mod( 'mwm_playsalud_footer_email', '' ) ) );
if ( '' === $footer_email ) {
	$footer_email = 'info@playsalud.com';
}

$footer_link = trim( (string) get_theme_mod( 'mwm_playsalud_footer_link', '' ) );
if ( '' === $footer_link ) {
	$footer_link = '#contacto';
}

$footer_link_title = trim( (string) get_theme_mod( 'mwm_playsalud_footer_link_title', '' ) );
if ( '' === $footer_link_title ) {
	$footer_link_title = __( 'Solicita una demo', 'mwm-playsalud' );
}

$footer_copyright = trim( (string) get_theme_mod( 'mwm_playsalud_footer_copyright', '' ) );
if ( '' === $footer_copyright ) {
	/* translators: %1$s: current year, %2$s: reserved rights text. */
	$footer_copyright = sprintf(
		'%1$s PlaySalud. %2$s',
		gmdate( 'Y' ),
		__( 'Todos los derechos reservados.', 'mwm-playsalud' )
	);
}

$social_links_raw = get_theme_mod( 'mwm_playsalud_social_links', '[]' );
$social_links_decoded = json_decode( (string) $social_links_raw, true );
$social_links = array();

if ( is_array( $social_links_decoded ) ) {
	foreach ( $social_links_decoded as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$image_id = isset( $item['image_id'] ) ? absint( $item['image_id'] ) : 0;
		$text     = isset( $item['text'] ) ? esc_url_raw( (string) $item['text'] ) : '';
		$text     = trim( $text );

		if ( 0 === $image_id && '' === $text ) {
			continue;
		}

		$social_links[] = array(
			'image_id' => $image_id,
			'text'     => $text,
		);
	}
}
?>
<footer class="mwm-footer">
	<div class="mwm-container">
		<div class="mwm-footer__grid">
			<div class="mwm-footer__brand">
				<?php if ( $footer_logo_id ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mwm-footer__logo" rel="home" aria-label="<?php esc_attr_e( 'PlaySalud', 'mwm-playsalud' ); ?>">
						<?php
						echo wp_get_attachment_image( $footer_logo_id, 'full', false, array( 'class' => 'custom-logo' ) );
						?>
					</a>
				<?php elseif ( has_custom_logo() ) : ?>
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
				<p class="mwm-footer__tagline"><?php echo esc_html( $footer_description ); ?></p>
				<?php if ( ! empty( $social_links ) ) : ?>
					<div class="mwm-footer__social">
						<p class="mwm-footer__title"><?php esc_html_e( 'Redes Sociales', 'mwm-playsalud' ); ?></p>
						<ul class="mwm-footer__social-list">
							<?php foreach ( $social_links as $social_item ) : ?>
								<li class="mwm-footer__social-item">
									<?php if ( '' !== $social_item['text'] ) : ?>
										<a class="mwm-footer__social-link" href="<?php echo esc_url( $social_item['text'] ); ?>" target="_blank" rel="noopener noreferrer">
									<?php endif; ?>
									<?php if ( ! empty( $social_item['image_id'] ) ) : ?>
										<?php
										echo wp_get_attachment_image(
											$social_item['image_id'],
											'thumbnail',
											false,
											array(
												'class'   => 'mwm-footer__social-icon',
												'loading' => 'lazy',
											)
										);
										?>
									<?php endif; ?>
									<?php if ( '' !== $social_item['text'] ) : ?>
										</a>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
			<div>
				<p class="mwm-footer__title"><?php echo esc_html( $footer_menu_title_1 ); ?></p>
				<?php if ( has_nav_menu( 'primary' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'container'      => false,
							'menu_class'     => 'mwm-footer__links',
						)
					);
					?>
				<?php else : ?>
					<ul class="mwm-footer__links">
						<li><a href="#verticales">PlayCare</a></li>
						<li><a href="#curso">PlayAcademy</a></li>
						<li><a href="#about"><?php esc_html_e( 'Sobre PlaySalud', 'mwm-playsalud' ); ?></a></li>
						<li><a href="#instituciones"><?php esc_html_e( 'Para instituciones', 'mwm-playsalud' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>
			<div>
				<p class="mwm-footer__title"><?php echo esc_html( $footer_menu_title_2 ); ?></p>
				<ul class="mwm-footer__links">
					<li><a href="mailto:<?php echo esc_attr( antispambot( $footer_email ) ); ?>"><?php echo esc_html( antispambot( $footer_email ) ); ?></a></li>
					<li><a href="<?php echo esc_url( $footer_link ); ?>"><?php echo esc_html( $footer_link_title ); ?></a></li>
				</ul>
			</div>
			<div>
				<p class="mwm-footer__title"><?php echo esc_html( $footer_menu_title_3 ); ?></p>
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'container'      => false,
							'menu_class'     => 'mwm-footer__links',
						)
					);
					?>
				<?php else : ?>
					<ul class="mwm-footer__links">
						<li><a href="#"><?php esc_html_e( 'Aviso legal', 'mwm-playsalud' ); ?></a></li>
						<li><a href="#"><?php esc_html_e( 'Privacidad', 'mwm-playsalud' ); ?></a></li>
						<li><a href="#"><?php esc_html_e( 'Cookies', 'mwm-playsalud' ); ?></a></li>
					</ul>
				<?php endif; ?>
			</div>
		</div>
		<div class="mwm-footer__bottom">
			<span><?php echo esc_html( $footer_copyright ); ?></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
