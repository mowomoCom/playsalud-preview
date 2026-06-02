<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow' => 'PlayCare',
	'title'   => 'Modulos de cuidados en formato video.',
	'items'   => array(
		array(
			'title' => 'Cancer colorrectal',
			'meta'  => '35 videos',
		),
		array(
			'title' => 'RICA Especialidades',
			'meta'  => '42 videos',
		),
		array(
			'title' => 'RICA General',
			'meta'  => '20 videos',
		),
		array(
			'title' => 'Incontinencia fecal',
			'meta'  => '20 videos',
		),
		array(
			'title' => 'Que saber de la anestesia',
			'meta'  => '15 videos',
		),
		array(
			'title' => 'Ostomias',
			'meta'  => '10 videos',
		),
	),
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
?>
<section id="modulos" class="mwm-home-section mwm-modulos">
	<div class="mwm-container">
		<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<h2><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
		<div class="mwm-modulos__grid">
			<?php foreach ( $items as $item ) : ?>
				<article class="mwm-modulos__card">
					<h3><?php echo esc_html( isset( $item['title'] ) ? (string) $item['title'] : '' ); ?></h3>
					<p><?php echo esc_html( isset( $item['meta'] ) ? (string) $item['meta'] : '' ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
