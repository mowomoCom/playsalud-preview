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
		<p class="mwm-eyebrow-stats mwm-eyebrow stats-eyebrow eyebrow"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></p>
		<div class="mwm-stats__grid stats-grid">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$raw_value = isset( $item['value'] ) ? trim( (string) $item['value'] ) : '';
				$label     = isset( $item['label'] ) ? (string) $item['label'] : '';
				$main      = $raw_value;
				$suffix    = '';
				$is_text   = false;

				if ( '' !== $raw_value && preg_match( '/^(\d+)\s*([^\d\s]+)$/u', $raw_value, $parts ) ) {
					$main   = $parts[1];
					$suffix = $parts[2];
				} elseif ( preg_match( '/\s/u', $raw_value ) || preg_match( '/[a-z]/iu', $raw_value ) ) {
					$is_text = true;
				}
				?>
				<article class="mwm-stats__item stat reveal">
					<div class="mwm-stats__value stat-value<?php echo $is_text ? ' text stat-value--text' : ''; ?>">
						<?php echo esc_html( $main ); ?>
						<?php if ( '' !== $suffix ) : ?>
							<span class="small"><?php echo esc_html( $suffix ); ?></span>
						<?php endif; ?>
					</div>
					<p class="mwm-stats__label stat-label"><?php echo esc_html( $label ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
