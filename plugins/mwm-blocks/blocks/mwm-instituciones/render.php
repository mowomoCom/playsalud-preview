<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$defaults = array(
	'eyebrow'          => 'Para instituciones',
	'title'            => 'Una plataforma para cada tipo de institucion.',
	'subtitle'         => 'PlaySalud se despliega por modulos, packs tematicos, campus, intranet, QR, marca blanca o integracion en plataformas institucionales. Adaptamos la propuesta a tu organizacion.',
	'ctaPrimaryText'   => 'Hablar con el equipo comercial',
	'ctaPrimaryUrl'    => '#contacto',
	'ctaSecondaryText' => 'Solicitar propuesta B2B',
	'ctaSecondaryUrl'  => '#contacto',
	'items'            => array(
		array(
			'title'       => 'Hospitales',
			'description' => 'Rutas clinicas + formacion.',
			'iconUrl'     => '',
			'iconAlt'     => 'Hospitales',
		),
		array(
			'title'       => 'Aseguradoras',
			'description' => 'Mejora de experiencia del asegurado.',
			'iconUrl'     => '',
			'iconAlt'     => 'Aseguradoras',
		),
		array(
			'title'       => 'Universidades',
			'description' => 'Integracion en LMS o campus.',
			'iconUrl'     => '',
			'iconAlt'     => 'Universidades',
		),
		array(
			'title'       => 'Sociedades cientificas',
			'description' => 'Cursos co-brandeados.',
			'iconUrl'     => '',
			'iconAlt'     => 'Sociedades cientificas',
		),
		array(
			'title'       => 'Asociaciones de pacientes',
			'description' => 'Informacion fiable y accesible.',
			'iconUrl'     => '',
			'iconAlt'     => 'Asociaciones de pacientes',
		),
		array(
			'title'       => 'Industria sanitaria',
			'description' => 'Patrocinio educativo responsable.',
			'iconUrl'     => '',
			'iconAlt'     => 'Industria sanitaria',
		),
	),
);

$attributes = wp_parse_args( is_array( $attributes ) ? $attributes : array(), $defaults );
$items      = is_array( $attributes['items'] ) ? $attributes['items'] : $defaults['items'];
$section_id = ! empty( $attributes['anchor'] ) ? sanitize_title( (string) $attributes['anchor'] ) : 'instituciones';
$wrapper_attributes = get_block_wrapper_attributes(
	array(
		'id'    => $section_id,
		'class' => 'mwm-home-section mwm-instituciones',
	)
);
?>
<section <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="mwm-container">
		<div class="section-header reveal">
			<div class="mwm-eyebrow mwm-eyebrow-instituciones"><?php echo wp_kses_post( $attributes['eyebrow'] ); ?></div>
			<h2 class="section-title mwm-instituciones__title"><?php echo wp_kses_post( $attributes['title'] ); ?></h2>
			<p class="section-subtitle"><?php echo wp_kses_post( $attributes['subtitle'] ); ?></p>
		</div>

		<div class="inst-grid">
			<?php foreach ( $items as $item ) : ?>
				<?php
				$item_title       = isset( $item['title'] ) ? (string) $item['title'] : '';
				$item_description = isset( $item['description'] ) ? (string) $item['description'] : '';
				$item_icon_url    = isset( $item['iconUrl'] ) ? (string) $item['iconUrl'] : '';
				$item_icon_alt    = isset( $item['iconAlt'] ) ? (string) $item['iconAlt'] : '';
				?>
				<article class="inst-card reveal">
					<div class="inst-icon">
						<?php if ( ! empty( $item_icon_url ) ) : ?>
							<img src="<?php echo esc_url( $item_icon_url ); ?>" alt="<?php echo esc_attr( $item_icon_alt ? $item_icon_alt : $item_title ); ?>" loading="lazy" />
						<?php endif; ?>
					</div>
					<div>
						<h3 class="inst-title"><?php echo esc_html( $item_title ); ?></h3>
						<p class="inst-desc"><?php echo esc_html( $item_description ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="inst-cta-row">
			<?php if ( ! empty( $attributes['ctaPrimaryText'] ) ) : ?>
				<a href="<?php echo esc_url( $attributes['ctaPrimaryUrl'] ); ?>" class="mwm-btn mwm-btn--md mwm-btn--primary">
					<?php echo esc_html( $attributes['ctaPrimaryText'] ); ?>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" focusable="false"><path d="M3 8h10m-4-4l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
				</a>
			<?php endif; ?>
			<?php if ( ! empty( $attributes['ctaSecondaryText'] ) ) : ?>
				<a href="<?php echo esc_url( $attributes['ctaSecondaryUrl'] ); ?>" class="mwm-btn mwm-btn--ghost mwm-btn--md">
					<?php echo esc_html( $attributes['ctaSecondaryText'] ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>
