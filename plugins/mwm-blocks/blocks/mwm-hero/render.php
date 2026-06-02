<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow'       => 'Plataforma de salud audiovisual',
	'title'         => 'Salud en video. Seria cuando toca, cercana cuando ayuda.',
	'lead'          => 'PlaySalud transforma informacion clinica compleja en videos breves, claros y basados en evidencia para pacientes y profesionales.',
	'primaryText'   => 'Solicitar demo institucional',
	'primaryUrl'    => '#contacto',
	'secondaryText' => 'Explorar PlayCare y PlayAcademy',
	'secondaryUrl'  => '#verticales',
	'imageUrl'      => '',
	'imageAlt'      => 'PlaySalud',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );

$image_url = ! empty( $attributes['imageUrl'] )
	? $attributes['imageUrl']
	: get_stylesheet_directory_uri() . '/assets/images/hero-real.jpg';
?>
<section id="hero" class="mwm-home-section mwm-hero">
	<div class="mwm-container mwm-hero__grid">
		<div>
			<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
			<h1 class="mwm-hero__title"><?php echo wp_kses_post( $attributes['title'] ); ?></h1>
			<p class="mwm-hero__lead"><?php echo wp_kses_post( $attributes['lead'] ); ?></p>
			<div class="mwm-hero__actions">
				<a href="<?php echo esc_url( $attributes['primaryUrl'] ); ?>" class="mwm-btn mwm-btn--primary mwm-btn--lg"><?php echo esc_html( $attributes['primaryText'] ); ?></a>
				<a href="<?php echo esc_url( $attributes['secondaryUrl'] ); ?>" class="mwm-btn mwm-btn--ghost mwm-btn--lg"><?php echo esc_html( $attributes['secondaryText'] ); ?></a>
			</div>
		</div>
		<div class="mwm-hero__visual">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $attributes['imageAlt'] ); ?>">
		</div>
	</div>
</section>
