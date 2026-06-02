<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow' => 'Datos clave',
	'items'   => array(
		array(
			'value' => '10',
			'label' => 'modulos clinicos en PlayCare',
		),
		array(
			'value' => '180+',
			'label' => 'videos animados basados en evidencia',
		),
		array(
			'value' => '56h',
			'label' => 'de formacion en IA sanitaria',
		),
		array(
			'value' => 'B2B + B2C',
			'label' => 'institucional y acceso individual',
		),
	),
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
?>
<section id="stats" class="mwm-home-section mwm-stats">
	<div class="mwm-container">
		<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<div class="mwm-stats__grid">
			<?php foreach ( $items as $item ) : ?>
				<article class="mwm-stats__item">
					<strong><?php echo esc_html( isset( $item['value'] ) ? (string) $item['value'] : '' ); ?></strong>
					<span><?php echo esc_html( isset( $item['label'] ) ? (string) $item['label'] : '' ); ?></span>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
