<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow'          => 'Dos brazos, un lenguaje',
	'title'            => 'PlayCare y PlayAcademy.',
	'careTitle'        => 'PlayCare',
	'careText'         => 'Videos de cuidados de salud por modulos para instituciones y equipos clinicos.',
	'careButtonText'   => 'Ver modulos',
	'careButtonUrl'    => '#modulos',
	'academyTitle'     => 'PlayAcademy',
	'academyText'      => 'Cursos acreditados para sanitarios con itinerario progresivo en IA aplicada.',
	'academyButtonText' => 'Ver curso',
	'academyButtonUrl' => '#curso',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
?>
<section id="verticales" class="mwm-home-section mwm-verticales">
	<div class="mwm-container">
		<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<h2 class="mwm-verticales__title"><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
		<div class="mwm-verticales__grid">
			<article class="mwm-verticales__card">
				<h3><?php echo esc_html( $attributes['careTitle'] ); ?></h3>
				<p><?php echo esc_html( $attributes['careText'] ); ?></p>
				<a href="<?php echo esc_url( $attributes['careButtonUrl'] ); ?>" class="mwm-btn mwm-btn--ghost mwm-btn--md"><?php echo esc_html( $attributes['careButtonText'] ); ?></a>
			</article>
			<article class="mwm-verticales__card">
				<h3><?php echo esc_html( $attributes['academyTitle'] ); ?></h3>
				<p><?php echo esc_html( $attributes['academyText'] ); ?></p>
				<a href="<?php echo esc_url( $attributes['academyButtonUrl'] ); ?>" class="mwm-btn mwm-btn--primary mwm-btn--md"><?php echo esc_html( $attributes['academyButtonText'] ); ?></a>
			</article>
		</div>
	</div>
</section>
