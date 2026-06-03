<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow'   => 'Capitulos de muestra',
	'title'     => 'Mira algunos capitulos completos.',
	'items'     => array(
		array( 'title' => 'Como cuidar tu herida postoperatoria' ),
		array( 'title' => 'Preparacion para consulta preanestesica' ),
		array( 'title' => 'Vivir con una ostomia' ),
		array( 'title' => 'Entender el cancer colorrectal' ),
	),
	'prevLabel' => 'Anterior',
	'nextLabel' => 'Siguiente',
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
$section_id = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : 'muestra';
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
		'class' => 'mwm-home-section mwm-muestra',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="mwm-container">
		<p class="mwm-eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<h2><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
		<div class="mwm-muestra__track" data-mwm-muestra-track>
			<?php foreach ( $items as $item ) : ?>
				<article class="mwm-muestra__slide"><span><?php echo esc_html( isset( $item['title'] ) ? (string) $item['title'] : '' ); ?></span></article>
			<?php endforeach; ?>
		</div>
		<div class="mwm-muestra__nav">
			<button type="button" class="mwm-btn mwm-btn--ghost mwm-btn--sm" data-mwm-prev><?php echo esc_html( $attributes['prevLabel'] ); ?></button>
			<button type="button" class="mwm-btn mwm-btn--ghost mwm-btn--sm" data-mwm-next><?php echo esc_html( $attributes['nextLabel'] ); ?></button>
		</div>
	</div>
</section>
