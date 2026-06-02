<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow' => 'Plataforma sanitaria audiovisual',
	'title'   => 'Educacion sanitaria basada en evidencia para pacientes, profesionales e instituciones.',
	'lead'    => 'Videos breves, claros y revisados por profesionales. Listos para integrarse en hospitales, aseguradoras y universidades.',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
?>
<section id="banner" class="mwm-home-section mwm-banner">
	<div class="mwm-container mwm-banner__inner">
		<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<h2><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
		<p><?php echo wp_kses_post( $attributes['lead'] ); ?></p>
	</div>
</section>
