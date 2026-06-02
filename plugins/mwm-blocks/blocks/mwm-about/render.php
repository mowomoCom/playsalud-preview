<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow' => 'Quienes somos',
	'title'   => 'Un equipo serio con un lenguaje propio.',
	'lead'    => 'PlaySalud nace de la conviccion de que la salud merece una voz audiovisual propia: rigurosa, humana y util.',
	'quote'   => 'Hacemos videos que un profesional firmaria sin incomodidad y que un paciente entiende sin sentirse infantilizado.',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
?>
<section id="about" class="mwm-home-section mwm-about">
	<div class="mwm-container mwm-about__grid">
		<div>
			<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
			<h2><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
			<p><?php echo wp_kses_post( $attributes['lead'] ); ?></p>
		</div>
		<aside class="mwm-about__quote">
			<p><?php echo wp_kses_post( $attributes['quote'] ); ?></p>
		</aside>
	</div>
</section>
