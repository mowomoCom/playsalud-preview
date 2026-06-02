<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow' => 'Para instituciones',
	'title'   => 'Una plataforma para cada tipo de institucion.',
	'items'   => array(
		array(
			'title'       => 'Hospitales',
			'description' => 'Rutas clinicas + formacion.',
		),
		array(
			'title'       => 'Aseguradoras',
			'description' => 'Mejora de experiencia del asegurado.',
		),
		array(
			'title'       => 'Universidades',
			'description' => 'Integracion en LMS o campus.',
		),
		array(
			'title'       => 'Sociedades cientificas',
			'description' => 'Cursos co-brandeados.',
		),
		array(
			'title'       => 'Asociaciones de pacientes',
			'description' => 'Informacion fiable y accesible.',
		),
		array(
			'title'       => 'Industria sanitaria',
			'description' => 'Patrocinio educativo responsable.',
		),
	),
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
?>
<section id="instituciones" class="mwm-home-section mwm-instituciones">
	<div class="mwm-container">
		<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<h2><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
		<div class="mwm-instituciones__grid">
			<?php foreach ( $items as $item ) : ?>
				<article class="mwm-instituciones__card">
					<h3><?php echo esc_html( isset( $item['title'] ) ? (string) $item['title'] : '' ); ?></h3>
					<p><?php echo esc_html( isset( $item['description'] ) ? (string) $item['description'] : '' ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
